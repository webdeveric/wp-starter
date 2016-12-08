<?php

namespace webdeveric\WPStarter;

use Exception;
use Composer\Script\Event;
use Composer\Util\Filesystem;

class Setup
{
    const SECRET_KEY_URL = 'https://api.wordpress.org/secret-key/1.1/salt/';

    protected $event;
    protected $fs;
    protected $wpInstallDir;

    protected function __construct(Event $event, Filesystem $fs)
    {
        $this->event = $event;
        $this->fs = $fs;
        $this->wpInstallDir = $this->getExtra('wordpress-install-dir', false);

        if (! $this->wpInstallDir) {
            throw new SetupException('wordpress-install-dir not set');
        }

        $this->run();
    }

    public static function getInstance(Event $event)
    {
        return new self($event, new Filesystem());
    }

    protected function getComposer()
    {
        return $this->event->getComposer();
    }

    protected function getPackage()
    {
        return $this->getComposer()->getPackage();
    }

    protected function getExtra($key = null, $default = '')
    {
        $extra = $this->getPackage()->getExtra();

        if (isset($key)) {
            return array_key_exists($key, $extra) ? $extra[ $key ] : $default;
        }

        return $extra;
    }

    protected function getIO()
    {
        return $this->event->getIO();
    }

    protected function info($message)
    {
        $this->getIO()->write("<info>{$message}</info>");
    }

    protected function comment($message)
    {
        if ( $this->getIO()->isVerbose() ) {
            $this->getIO()->write("<comment>{$message}</comment>");
        }
    }

    protected function error($message)
    {
        $this->getIO()->writeError("<error>{$message}</error>");
    }

    protected function fixIndexFile($src, $dest)
    {
        if (is_file($src) && is_readable($src)) {
            return file_put_contents(
                $dest,
                str_replace(
                    '/wp-blog-header.php',
                    "/{$this->wpInstallDir}/wp-blog-header.php",
                    file_get_contents($src)
                )
            );
        }

        return false;
    }

    protected function fetchSecrets()
    {
        $secrets = [];

        ini_set('auto_detect_line_endings', true);

        try {
            $this->comment('Fetching secret key values from WordPess.org API');

            $lines = file( self::SECRET_KEY_URL, FILE_IGNORE_NEW_LINES );

            foreach( $lines as $line ) {
                if ( preg_match("#define\('(?<key>[A-Z_]+)',\s*'(?<value>[^']+)'\);#", $line, $matches) ) {
                    $secrets[ $matches['key'] ] = $matches['value'];
                }
            }
        } catch ( Exception $error ) {
            $this->error( trim( $error->getMessage() ) );
        }

        return $secrets;
    }

    protected function maybeCreateEnvFile()
    {
        if (! file_exists(getcwd() . '/.env')) {
            $secrets = $this->fetchSecrets();

            $this->comment('Creating .env file from .env.example');

            $env = file_get_contents(getcwd() . '/.env.example');

            foreach( $secrets as $key => $value ) {
                $env = str_replace("{$key}=SECRET_GOES_HERE", "{$key}=\"{$value}\"", $env);
                $this->comment("Setting {$key}");
            }

            return file_put_contents(getcwd() . '/.env', $env);
        }

        return false;
    }

    protected function maybeCopyConfig()
    {
        if (! file_exists(getcwd() . '/wp-config.php')) {
            $this->comment('Attempting to copy wp-config-env.php to wp-config.php');

            return copy(getcwd() . '/wp-config-env.php', getcwd() . '/wp-config.php');
        } else {
            $this->comment('wp-config.php already exists');
        }

        return false;
    }

    protected function cleanUp()
    {
        $cms = realpath(getcwd() . '/' . $this->wpInstallDir);

        $garbage = array_filter([
            realpath($cms . '/wp-content'),
            realpath($cms . '/readme.html'),
            realpath($cms . '/license.txt'),
        ]);

        return array_walk($garbage, [ $this->fs, 'remove' ]);
    }

    public function run()
    {
        switch ($this->event->getName()) {
            case 'post-create-project-cmd':

                if ($this->maybeCreateEnvFile()) {
                    $this->info('.env file created');
                } else {
                    $this->error('.env file not created');
                }

                if ($this->maybeCopyConfig()) {
                    $this->info('wp-config-env.php copied to wp-config.php');
                } else {
                    $this->error('wp-config-env.php not copied');
                }

            case 'post-install-cmd':
            case 'post-update-cmd':

                $bytes = $this->fixIndexFile(
                    realpath(getcwd() . '/' . $this->wpInstallDir . '/index.php'),
                    getcwd() . '/index.php'
                );

                if ($bytes === false) {
                    $this->error('index.php file not fixed');
                } else {
                    $this->info("index.php file fixed ({$bytes} bytes written)");
                }

                if ($this->cleanUp()) {
                    $this->info('clean up complete');
                } else {
                    $this->error('clean up failed');
                }

                break;
            default:
                $this->comment('unhandled event: ' . $this->event->getName());
        }
    }
}
