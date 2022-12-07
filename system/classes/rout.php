<?php
class rout
{
    //Default Controller & Method & Param
    private  string | object $controller = "welcome";
    private string $method;
    private  array $param;
    private bool $flag = false;
    //----------------------------------

    public function __construct()
    {
        $url = $this->url();
        if (!empty($url)) {
            if (file_exists("../application/controllers/" . $url[0] . ".php")) {
                $this->controller = $url[0];
                unset($url[0]);
            } else {
                echo "<div style='background-color:silver;padding:10px;margin:0;text-align:center;font-size:1.5rem;'>Sorry <strong style='color:red;'>$url[0]</strong> is not a Class Controller</div>";
                exit;
            }
        }

        //Including Controller & Class Insialize
        require_once("../application/controllers/" . $this->controller . ".php");
        $this->controller = new $this->controller;
        //--------------------------------------

        if (isset($url[1]) && !empty($url)) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
                $this->flag = true;
            } else {
                echo "<div style='background-color:silver;padding:10px;margin:0;text-align:center;font-size:1.5rem;'>Sorry <strong style='color:red;'>$url[1]()</strong> Method are Not Found</div>";
                exit;
            }
        }

        if (!empty($url)) {
            $this->param = $url;
        } else {
            $this->param = [];
        }

        //Call Controller Releted Method
        if ($this->flag) {
            call_user_func([$this->controller, $this->method], ...$this->param);
        }
        //-----------------------------
    }

    //Split URL
    public function url()
    {
        if (isset($_GET['url'])) {
            $url = $_GET['url'];
            $url = rtrim($url);
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
    //-----------
}