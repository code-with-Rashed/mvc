<?php

namespace Management\Classes;

class File
{
    public static function exist(string $file): bool
    {
        if (file_exists($file)) {
            return true;
        }
        return false;
    }

    public static function remove(string $file): bool
    {
        if (self::exist($file)) {
            return true;
        }
        return false;
    }

    public static function upload(string $tmp, string $path_file): bool
    {
        if (move_uploaded_file($tmp, $path_file)) {
            return true;
        }
        return false;
    }
}
