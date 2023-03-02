<?php

namespace Management\Classes;

class Cookie
{
    public static function has(string $key)
    {
        if (isset($_COOKIE[$key])) {
            return true;
        }
        return false;
    }
    public static function get(string $key)
    {
        if (self::has($key)) {
            return $_COOKIE[$key];
        }
    }
    public static function set(string $key, string $value = null, int $expires_time = 0, string $path = null, string $domain = null, bool $secure = null, bool $http_only = null)
    {
        setcookie($key, $value, $expires_time, $path, $domain,  $secure, $http_only);
        return true;
    }
    public static function remove(string $key)
    {
        if (self::has($key)) {
            unset($_COOKIE[$key]);
            return true;
        }
        return false;
    }
    public static function clear()
    {
        $_COOKIE = [];
    }
}
