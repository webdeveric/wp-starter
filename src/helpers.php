<?php

namespace webdeveric\WPStarter;

function fileIsReadable($env)
{
    return $env && is_file($env) && is_readable($env);
}

function getEnvFilePath()
{
    $path  = $_SERVER['WP_ENV'] ?: false;

    if (fileIsReadable($path)) {
        return $path;
    }

    $paths = [ '.env', '../.env' ];
    $limit = count($paths);

    for ($i = 0 ; $i < $limit && ! $path ; ++$i) {
        $env = realpath($_SERVER['DOCUMENT_ROOT'] . '/' . $paths[ $i ]);

        if (fileIsReadable($env)) {
            $path = $env;
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
