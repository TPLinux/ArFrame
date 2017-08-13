<?php


class Session {
    
    public function __construct(){
        @ session_start();
    }

    public function set($name,$val){
        $_SESSION["$name"] = $val;
    }

    public function get($name){
        if(isset($_SESSION[$name]))
            return $_SESSION[$name];
        else
            return null;
    }

    public function destroy(){
        session_destroy();
    }

    public function checkS($name){

        if(isset($_SESSION[$name]) && $_SESSION[$name] != null && $_SESSION[$name] != "" && !empty($_SESSION[$name])){
            return true;
        }else{
            return false;
        }
    }
    
}


?>
