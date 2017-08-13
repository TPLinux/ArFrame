<?php
// error_reporting(0);
// config files
require_once("./config/cons.php");
require_once("./config/db.php");

// libs
require_once("./libs/Controller.php");
require_once("./libs/DB.php");
require_once("./libs/Model.php");
require_once("./libs/Session.php");
require_once("./libs/simple_html_dom.php");
require_once("./libs/View.php");
require_once("./libs/Route.php");


// Routes 
/*
 * Example :
 * $r->addRoute('/path','controllerName@methodIntoIt');
 * $r->addRoute('/users/(username)/slide/(slide_id)','controllerName@getUserSlide');
 * $r->addRoute('/users/get-data','controllerName@getAllData');
*/

// index controller
$r->addRoute("/index","index@index");
$r->addRoute("/users/(username)/slide/(slide_id)","index@userSlide");



if(isset($_GET['route'])){
    $route = "/" . rtrim($_GET['route'],"/");
    $r->getRoute($route);
}else{
    Controller::redirect(URL . "/index");
}

?>
