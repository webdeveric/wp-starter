<?php

namespace webdeveric\WPStarter;

function fileIsReadable($env)
{
    return $env && is_file($env) && is_readable($env);
}

function getEnvFilePath($key = 'WP_ENV')
{
    $path  = $_SERVER[ $key ] ?: false;

    if (fileIsReadable($path)) {
        return $path;
    }

    $paths = [ '../.env', '.env' ];

    for ($i = 0; $i < 2; ++$i) {
        $env = realpath($_SERVER['DOCUMENT_ROOT'] . '/' . $paths[ $i ]);

        if (fileIsReadable($env)) {
            $path = $env;
            break;
        }
    }

    return $path;
}

function getConfig($path)
{
    if (! $path) {
        return false;
    }

    $data = array_change_key_case(
        parse_ini_file($path, false, INI_SCANNER_RAW),
        CASE_UPPER
    );

    return function ($key, $default = '') use ($data) {
        $key = strtoupper($key);

        if (isset($data[ $key ])) {
            if ($data[ $key ] === 'true') {
                return true;
            }

            if ($data[ $key ] === 'false') {
                return false;
            }

            return $data[ $key ];
        }

        return $default;
    };
}
