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
composer create-project --repository '{"type":"git","url":"https://github.com/webdeveric/wp-starter.git"}' -s dev --prefer-dist --no-interaction -- webdeveric/wp-starter ./your-folder-here
```

### Local development

If you have [Docker](https://docs.docker.com/engine/installation/) and [Docker Compose](https://docs.docker.com/compose/install/) you can run `docker-compose up dev` to get started quickly.

I've included a sample DB. It will be created the first time you run `up`.

The username and password are both `wp`.

To install dependencies, you can run `docker-compose run --rm composer install`

## Useful links

- [Composer](https://getcomposer.org/)
- [WordPress Packagist](http://wpackagist.org)
- [WP Codex: Giving WordPress Its Own Directory](https://codex.wordpress.org/Giving_WordPress_Its_Own_Directory)
- [Install Docker](https://docs.docker.com/engine/installation/)
- [Install Docker Compose](https://docs.docker.com/compose/install/)
