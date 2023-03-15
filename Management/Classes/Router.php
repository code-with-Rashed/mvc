<?php

namespace Management\Classes;

class Router
{
    private static function get_uri(): string
    {
        return $_SERVER["REQUEST_URI"];
    }
    private static function url_matches(string $url): array|bool
    {
        (string) $subject = self::get_uri();
        if (preg_match($url, $subject, $matches)) {
            return $matches;
        }
        return false;
    }
    private static function parse_url(string $url): string
    {
        $parse_url = explode("/", $url);
        $final_url = "";
        foreach ($parse_url as $value) {
            if ($value) {
                $last = strlen($value) - 1;
                if ($value[0] === "{" && $value[$last] === "}") {
                    $final_url .= "/(\w+)";
                } else {
                    $final_url .= "/$value";
                }
            }
        }
        return $final_url;
    }
    private static function process(string $url, object|array $callback): void
    {
        (string) $pattern = self::parse_url($url);
        (string) $uri = APP_FOLDER . $pattern;
        (string) $url_match = "~^{$uri}/?$~";
        (array)(bool) $params = self::url_matches($url_match);
        if ($params) {
            $arguments = array_slice($params, 1);
            if (is_callable($callback)) {
                $callback(...$arguments);
            } else if (is_array($callback)) {
                (string) $class = $callback[0];
                (string) $method = $callback[1];
                (object) $object = new $class();
                $object->$method(...$arguments);
            }
        }
    }
    public static function get(string $url, object|array $callback)
    {
        if ($_SERVER["REQUEST_METHOD"] != "GET") {
            return;
        }
        self::process($url, $callback);
    }
    public static function put(string $url, object|array $callback)
    {
        if ($_SERVER["REQUEST_METHOD"] != "PUT") {
            return;
        }
        self::process($url, $callback);
    }
    public static function patch(string $url, object|array $callback)
    {
        if ($_SERVER["REQUEST_METHOD"] != "PATCH") {
            return;
        }
        self::process($url, $callback);
    }
    public static function delete(string $url, object|array $callback)
    {
        if ($_SERVER["REQUEST_METHOD"] != "DELETE") {
            return;
        }
        self::process($url, $callback);
    }
    public static function post(string $url, object|array $callback)
    {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            return;
        }
        self::process($url, $callback);
    }

    public static function __callStatic($name, $arguments)
    {
        die("This is non existing or private static method : $name()");
    }
}
