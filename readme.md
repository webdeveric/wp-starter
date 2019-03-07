# WP Starter

## WordPress dependency management with Composer

With Composer, you can define the WordPress core as a dependency so you never have to include it in your repo.
You can also specify which plugins and themes you want to require.

### WordPress core files

For this to work, the WordPress core needs to be put [in its own directory](https://codex.wordpress.org/Giving_WordPress_Its_Own_Directory).

The included [composer.json](composer.json) file is already set up to do all of this for you.

### Plugins and themes

If the plugins/themes you want are published to the wordpress.org SVN repo, they will also be available on [WordPress Packagist](http://wpackagist.org), which is a Composer repository that mirrors the SVN repo.

If you want to install a plugin from your version control system (Git, SVN, etc.), please follow [these instructions](https://getcomposer.org/doc/05-repositories.md#vcs).

The included [composer.json](composer.json) file has an example of getting a plugin from Github.

## Installation

Install [Composer](https://getcomposer.org/) before you do anything else.

### Use this repo as the basis for a new project

```
composer create-project -s dev --prefer-dist --no-interaction -- webdeveric/wp-starter ./your-folder-here
```

### Local development

Run `composer setup-hooks` to setup the git `pre-commit` hook. It will check coding standards and run tests when you commit.

I've included a [Dockerfile](Dockerfile) that is based on `php:7.3-apache`. Some additional modules, such as Xdebug, APC, and APCu are also installed.

A sample DB will be imported the first time you build. The WordPress username and password are both `wp`.

To get started, run the following:

:one: `make install`

:two: `make dev`

You may want to view the [Makefile](Makefile) to see all the commands.

## Useful links

- [Composer](https://getcomposer.org/)
- [WordPress Packagist](http://wpackagist.org)
- [WP Codex: Giving WordPress Its Own Directory](https://codex.wordpress.org/Giving_WordPress_Its_Own_Directory)
- [Install Docker](https://docs.docker.com/engine/installation/)
- [Install Docker Compose](https://docs.docker.com/compose/install/)
