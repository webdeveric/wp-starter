FROM php:8.5-apache

LABEL maintainer="eric@webdeveric.com"
LABEL com.webdeveric.wp-starter.service-id=wp-starter
LABEL com.webdeveric.wp-starter.component-id=wp-starter-web

ENV APP_ENV=production

RUN \
  apt-get update && apt-get install -y --no-install-recommends \
    libfreetype6 \
    libfreetype6-dev \
    libicu-dev \
    libxslt1-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libxpm-dev \
    libzip-dev

RUN docker-php-ext-configure gd --with-webp --with-jpeg --with-xpm --with-freetype

RUN docker-php-ext-install -j$(nproc) exif gd iconv intl mysqli pdo_mysql xsl zip

RUN \
  pecl channel-update pecl.php.net && \
  pecl install xdebug-3.5.0

RUN \
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
