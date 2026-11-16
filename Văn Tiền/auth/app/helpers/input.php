<?php

if (!function_exists('post_string')) {
    function post_string(string $key, bool $trim = true): string
    {
        $value = $_POST[$key] ?? '';

        if (!is_string($value)) {
            return '';
        }

        return $trim ? trim($value) : $value;
    }
}
