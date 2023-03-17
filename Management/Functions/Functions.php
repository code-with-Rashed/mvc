<?php

function view(string $view_file, ...$data): void
{
    $extension = !pathinfo($view_file, PATHINFO_EXTENSION) ? ".php" : "";
    if (file_exists(APP_ROOT . "/Resources/View/" . $view_file . $extension)) {
        include_once(APP_ROOT . "/Resources/View/" . $view_file . $extension);
    } else {
        die("This view file ($view_file$extension) are not exist !");
    }
}

function storage_path(string $path = ''): string
{
    return APP_ROOT . "/Public/Storage/" . $path;
}

function stored_file(string $file = '')
{
    return APP_URL . "/Public/Storage/" . $file;
}

function assets(string $asset = ''): string
{
    return APP_URL . "/Public/Assets/" . $asset;
}

function url(string $path = ''): string
{
    return APP_URL . $path;
}

function redirect(string $uri)
{
    header("Location:" . APP_URL . $uri);
}
