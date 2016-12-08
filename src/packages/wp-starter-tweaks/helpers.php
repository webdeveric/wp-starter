<?php

namespace webdeveric\WPStarterTweaks;

function str_starts_with($str, $word, $case_sensitive = true)
{
    $sub = mb_substr($str, 0, mb_strlen($word));

    if ($case_sensitive === false) {
        return strtolower($sub) === strtolower($word);
    }

    return $sub === $word;
}

function str_ends_with($str, $word, $case_sensitive = true)
{
    $sub = mb_substr($str, 0 - mb_strlen($word));

    if ($case_sensitive === false) {
        return strtolower($sub) === strtolower($word);
    }

    return $sub === $word;
}
