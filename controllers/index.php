<?php

class Index extends Controller{
    
    public function __construct(){
        parent::__construct();
        $this->view->dlang = $this->dlang;
    }

    public function index(){
        $this->view->view("index");
    }

    public function userSlide($data){
        print_r($data);
        $this->view->data = $data;
        $this->view->view('user-slide');
    }


}
?>
