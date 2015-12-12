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

```bash
composer install --prefer-dist --no-autoloader
```

### Deployment server: sample install script

This should get you started with your deployment script for Jenkins (or other deployment server).

```bash
#!/bin/bash -l -e
# set -x
source ~/.bashrc

# Install Composer
curl -sS https://getcomposer.org/installer | php

# Install dependencies
php composer.phar install --prefer-dist --no-autoloader

# Your custom deployment tasks go here.
```

## Useful links

- [Composer](https://getcomposer.org/)
- [WordPress Packagist](http://wpackagist.org)
- [WP Codex: Giving WordPress Its Own Directory](https://codex.wordpress.org/Giving_WordPress_Its_Own_Directory)
