<?php
// CSRF TOKEN 
namespace Management\Classes;

use Management\Classes\Token;
use Management\Classes\Session;

class Csrf
{
    public static function create(string $csrf_name = 'CSRF')
    {
        $csrf = Token::make();
        Session::set($csrf_name, $csrf);
        return $csrf;
    }
    public static function match(string $csrf_name, string $csrf_value)
    {
        if (Session::get($csrf_name) === $csrf_value) {
            return true;
        }
        return false;
    }
}
