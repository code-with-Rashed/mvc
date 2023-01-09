<?php
require_once("env.php");

//application constant variables
define("APP_URL", $APP_URL);
define("APP_FOLDER", $APP_FOLDER);
define("APP_ROOT", $_SERVER["DOCUMENT_ROOT"] . "/" . APP_FOLDER);
//------------------------------

//mysql database constant variables
define("MSDB_HOST", $MSDB_HOST);
define("MSDB_PORT", $MSDB_PORT);
define("MSDB_USERNAME", $MSDB_USERNAME);
define("MSDB_PASSWORD", $MSDB_PASSWORD);
define("MSDB_NAME", $MSDB_NAME);
//---------------------------------
