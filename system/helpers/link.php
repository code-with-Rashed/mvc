<?php
// MAKE CSS LINKS
function css_link(string $css_path):void{
    $url = BASEURL . "/" .$css_path;
    echo "<link href='$url' rel='stylesheet'>";
}
//---------------

// MAKE JS LINKS
function js_link(string $js_path):void{
    $url = BASEURL . "/" . $js_path;
    echo "<script src='$url'></script>";
}
//--------------