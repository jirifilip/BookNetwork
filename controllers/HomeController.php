<?php

class HomeController extends Controller {

    private $article;

    function __construct() {
        $this->setData(array(
            "title" => "Home",
            "heading" => "Homepage",
        ));
        
        
    }
    
    public function index() {
        $data = $this->getData();

        $this->render('wall.index', $data);
    }

    public function show() {

    }

}