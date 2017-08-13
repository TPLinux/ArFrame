<?php

define('DEFAULT_LANG','en');
define("URL","/ArFrame"); // the base url If the Base is '/' set the value empty define("URL","");

define("IMG_PATH", URL . "/public/img/");
define("CSS_PATH", URL . "/public/css/");
define("JS_PATH", URL . "/public/js/");
define("PUBLIC_PATH", URL . "/public/");

define("REGEX_INT",'/^[0-9]+$/');
define("REGEX_USERNAME",'/^[a-z][a-z0-9._]{3,19}$/');
define("REGEX_SPECIAL_CHAR",'/[\'"<>\\\]/'); // block this characters
define("REGEX_EMAIL",'/^[a-zA-Z0-9_.]+@[a-zA-Z0-9]+\.[a-zA-Z0-9.]+$/');
define("SECRET_CAPTCHA","");
define("ALL_LANG",'ar,en');
?>
