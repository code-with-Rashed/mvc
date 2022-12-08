<?php
class framework
{
    // VIEW FILE FIND THEN INCLUDING 
    protected function view(string $view_name, array $data = [])
    {
        if (file_exists("../application/views/$view_name.php")) {
            require_once("../application/views/$view_name.php");
        } else {
            echo "<div style='background-color:silver;padding:10px;margin:0;text-align:center;font-size:1.5rem;'>Sorry this view file <strong style='color:red;'>$view_name</strong> is not found !</div>";
            exit;
        }
    }
    //------------------------------

    // MODEL FILE FIND THEN INCLUDING
    protected function model(string $model_name)
    {
        if (file_exists("../application/models/$model_name.php")) {
            require_once("../application/models/$model_name.php");
            return new $model_name;
        } else {
            echo "<div style='background-color:silver;padding:10px;margin:0;text-align:center;font-size:1.5rem;'>Sorry this model file <strong style='color:red;'>$model_name</strong> is not found !</div>";
            exit;
        }
    }
    //--------------------------------

    // FINDING HELPER FILE THEN INCLUDING
    protected function helper(string $helper_name)
    {
        if (file_exists("../system/helpers/$helper_name.php")) {
            require_once ("../system/helpers/$helper_name.php");
        } else {
            echo "<div style='background-color:silver;padding:10px;margin:0;text-align:center;font-size:1.5rem;'>Sorry this helper file <strong style='color:red;'>$helper_name</strong> is not found !</div>";
            exit;
        }
    }
    //-----------------------------------

}
