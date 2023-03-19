<?php

namespace Management\Classes;

class Session
{
    public static function secure(int $life_time = 0, string $path = null, string $domain = null, bool $secure = null, bool $http_only = null)
    {
        session_set_cookie_params($life_time, $path, $domain, $secure, $http_only);
    }
    public static function start()
    {
        if (!session_id()) {
            session_start();
        }
    }
    public static function has(string $key)
    {
        self::start();
        return array_key_exists($key, $_SESSION);
    }
    public static function get(string $key)
    {
        if (self::has($key)) {
            return $_SESSION[$key];
        }
    }
    public static function set(string $key, mixed $value = null)
    {
        self::start();
        $_SESSION[$key] = $value;
        return true;
    }
    public static function remove(string $key)
    {
        if (self::has($key)) {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }
    public static function clear()
    {
        self::start();
        $_SESSION = [];
    }
}
