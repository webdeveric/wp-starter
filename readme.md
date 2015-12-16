# WP Starter

## WordPress dependency management with Composer

With Composer, you can define the WordPress core as a dependency so you never have to include it in your repo.
You can also specify which plugins and themes you want to require.

### WordPress core files

For this to work, the WordPress core needs to be put [in its own directory](https://codex.wordpress.org/Giving_WordPress_Its_Own_Directory).

The included [composer.json](composer.json) file is already set up to do all of this for you.

_The `post-install-cmd` scripts are currently only tested on Linux and OS X._

### Plugins and themes

If the plugins/themes you want are published to the wordpress.org SVN repo, they will also be available on [WordPress Packagist](http://wpackagist.org), which is a Composer repository that mirrors the SVN repo.

If you want to install a plugin from your version control system (Git, SVN, etc.), please follow [these instructions](https://getcomposer.org/doc/05-repositories.md#vcs).

The included [composer.json](composer.json) file has an example of getting a plugin from Github.

## Installation

### Local development environment

Install [Composer](https://getcomposer.org/) then run this from the project directory.

```bash
composer install
cp ./.env.example ./.env
cp ./wp-config-env.php ./wp-config.php
```

For the config to work, you need to create a `WP_ENV` environment variable that contains the file system path to the `.env` file.
An example `.env` file is included in this repo.

If you're using Apache for local development, you can add this to your `.htaccess` file:

```apacheconf
SetEnv WP_ENV /your/path/to/.env
```

### Deployment server

This should get you started with your deployment script for Jenkins (or other deployment server).

```bash
#!/bin/bash -l -e
# set -x
source ~/.bashrc

# Install Composer
curl -sS https://getcomposer.org/installer | php

# Install dependencies
php composer.phar install --no-dev --prefer-dist --no-interaction

# Setup wp-config.php
mv ./wp-config-env.php ./wp-config.php
rm -f ./wp/wp-config-sample.php

# Remove files that are not needed to run in the production environment
find . -name ".git*" -exec rm -rf {} \;
find . -iname "readme.{txt,md}" -exec rm -rf {} \;

# Remove composer when done
rm -f ./composer.phar

# Your custom packaging and deployment tasks go here.
```

## Useful links

- [Composer](https://getcomposer.org/)
- [WordPress Packagist](http://wpackagist.org)
- [WP Codex: Giving WordPress Its Own Directory](https://codex.wordpress.org/Giving_WordPress_Its_Own_Directory)
