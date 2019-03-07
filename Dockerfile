FROM php:7.3-apache

LABEL maintainer "eric@webdeveric.com"

ENV APP_ENV=production

RUN \
  apt-get update && apt-get install -y \
    libfreetype6-dev \
    libicu-dev \
    libxslt1-dev \
    libfreetype6 \
    libjpeg-dev \
    libpng-dev \
    libxslt1.1 \
    libzip-dev \
    --no-install-recommends && \
  docker-php-ext-configure gd --with-freetype-dir=/usr --with-png-dir=/usr --with-jpeg-dir=/usr && \
  docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) exif gd iconv intl mysqli opcache xsl zip && \
  pecl channel-update pecl.php.net && \
  pecl install apcu-5.1.17 apcu_bc-1.0.5 xdebug-2.7.0 && \
  docker-php-ext-enable apcu && \
  docker-php-ext-enable --ini-name zzz-apc.ini apc && \
  apt-get purge -y --auto-remove \
    libfreetype6-dev \
    libicu-dev \
    libxslt1-dev \
    libyaml-dev && \
  rm -r /var/lib/apt/lists/*

WORKDIR /app/

COPY ./docker/apache/apache.conf /etc/apache2/conf-available/zzz-config.conf
COPY ./docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/zzz-overrides.ini

COPY ./composer.* /app/
COPY ./vendor /app/vendor
COPY ./packages /app/packages
COPY ./public /app/public

# Set ownership and permissions to the minimum needed to run.
# - Permissions should be read-only for user and group. No access otherwise.
# - root is the default owner.
# - Set www-data as the group. Doing it this way prevents Apache from chmod'ing files.
# - Apache needs RW access to the uploads directory.
#   Making www-data the owner and group allows WordPress to create folders correctly.
RUN [ "/bin/bash", "-c", "\
  mkdir -p /app/public/wp-content/uploads && \
  chgrp -R www-data /app/{packages,public,vendor} && \
  find /app/{packages,public,vendor} -type d -exec chmod 0550 {} \\; && \
  find /app/{packages,public,vendor} -type f -exec chmod 0440 {} \\; && \
  chown -R www-data /app/public/wp-content/uploads && \
  chmod -R u+w,g+w /app/public/wp-content/uploads \
" ]

RUN a2enmod expires headers rewrite unique_id && a2enconf zzz-config

COPY ./docker/apache/start-apache /usr/local/bin/

CMD ["start-apache"]
