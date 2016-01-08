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

**WARNING:** Do not run this locally. It will delete files not needed on a production server, like your `.git` folder.

```bash
#!/bin/bash -l -e
# set -x
source ~/.bashrc

RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
YELLOW='\033[0;33m'
NC='\033[0m' # No color

# Install Composer
curl -sS https://getcomposer.org/installer | php

# Validate first
php composer.phar validate -A --strict

# Install dependencies
php composer.phar install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Remove composer when done
rm -f ./composer.phar

# Setup wp-config.php
if [ -f ./wp-config.php ]; then
  echo -e "${PURPLE}wp-config.php already exists${NC}"
fi

if [ -f ./wp-config-env.php ] && [ ! -f ./wp-config.php ]; then
  echo -e "${YELLOW}Creating wp-config.php${NC}"
  mv ./wp-config-env.php ./wp-config.php

  if [ -f ./wp-config.php ]; then
    echo -e "${GREEN}wp-config.php has been created${NC}"
  fi
fi

if [ ! -f ./wp-config.php ]; then
  echo -e "${RED}wp-config.php is missing${NC}"
  exit 1
fi

# Remove files that are not needed to run in the production environment
rm -f ./wp/wp-config-sample.php
find . -name ".git*" -exec rm -rf {} \;
find . -iname "readme.{txt,md}" -exec rm -rf {} \;
echo -e "${BLUE}Clean up complete${NC}"

# Your custom packaging and deployment tasks go here.
```

## Useful links

- [Composer](https://getcomposer.org/)
- [WordPress Packagist](http://wpackagist.org)
- [WP Codex: Giving WordPress Its Own Directory](https://codex.wordpress.org/Giving_WordPress_Its_Own_Directory)
