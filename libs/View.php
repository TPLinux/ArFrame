<?php
class View extends Controller{
    
    public function __construct(){
        
    }
    public function view($tpl){
        $path = "views/".$tpl.".php";
        if(file_exists($path)){
            require_once("$path");
        }else{
            echo "The '<b style='color:blue;'>views/$tpl.php</b>' template isn't avilable" ;
            die();
        }
        return $this;
    }
}
?>
