<?php

namespace webdeveric\WPStarter;

use Composer\Script\Event;
use Composer\Util\Filesystem;

class Setup
{
    protected $event;
    protected $fs;
    protected $wpInstallDir;

    private function __construct(Event $event, Filesystem $fs)
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

    public function getComposer()
    {
        return $this->event->getComposer();
    }

    public function getPackage()
    {
        return $this->getComposer()->getPackage();
    }

    public function getExtra($key = null, $default = '')
    {
        $extra = $this->getPackage()->getExtra();

        if (isset($key)) {
            return array_key_exists($key, $extra) ? $extra[ $key ] : $default;
        }

        return $extra;
    }

    public function getIO()
    {
        return $this->event->getIO();
    }

    public function info($message)
    {
        $this->getIO()->write("<info>{$message}</info>");
    }

    public function error($message)
    {
        $this->getIO()->writeError("<error>{$message}</error>");
    }

    public function fixIndexFile($src, $dest)
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

    public function maybeCopyEnv()
    {
        if (! file_exists(getcwd() . '/.env')) {
            return copy(getcwd() . '/.env.example', getcwd() . '/.env');
        }

        return false;
    }

    public function maybeCopyConfig()
    {
        if (! file_exists(getcwd() . '/wp-config.php')) {
            return copy(getcwd() . '/wp-config-env.php', getcwd() . '/wp-config.php');
        }

        return false;
    }

    public function cleanUp()
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

                if ($this->maybeCopyEnv()) {
                    $this->info('.env.example copied to .env');
                } else {
                    $this->error('.env.example not copied');
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
                $this->error('unknown event');
        }
    }
}
