<?php

namespace Management\Classes;

class Hash
{
    public static function make(string $value): string
    {
        return password_hash($value, PASSWORD_BCRYPT);
    }
    public static function match(string $value, string $hash_value): bool
    {
        return password_verify($value, $hash_value);
    }
}
