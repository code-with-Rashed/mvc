<?php

namespace Management\Classes;

class Token
{
    public static function make(){
        return bin2hex(random_bytes(16));
    }
}
