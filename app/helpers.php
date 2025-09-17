<?php

if (!function_exists('oldValueOrDefault')) {
    function oldValueOrDefault($field, $default = '0.0')
    {
        return old($field) === null ? $default : old($field);
    }
}
