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

    $parentDir = dirname(__DIR__);

    $docRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?: $parentDir);

    $paths = [
        realpath($parentDir . '/.env'),
        realpath($parentDir . '/../.env'),
    ];

    if ( $parentDir !== $docRoot ) {
        $paths[] = realpath($docRoot . '/.env');
        $paths[] = realpath($docRoot . '/../.env');
    }

    $paths = array_filter($paths, __NAMESPACE__ . '\fileIsReadable');

    return $paths[ 0 ] ?: false;
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
