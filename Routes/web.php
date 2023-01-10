<?php

use Management\Classes\Router;

Router::get("/mvc/view",function(){
 return view("testView");
});