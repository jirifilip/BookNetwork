<?php

    class WallController extends Controller {

        public $user;

        function __construct() {
            $this->user = new User();
        }
    
        public function index() {
            if (!Session::exists("username")) {
                URLUtils::redirect('/');
            }

            $username = Session::get('username');
            $user = $this->user->getFullProfile($username);

            $this->render('wall.index', [
                "title" => "Zeď",
                "profile" => $user
            ]);
        }

        public function create() {
            echo "WallController create method"; 
        }

        public function show() {
            echo "WallController show method"; 
        }

        public function store() {
            echo "WallController store method"; 
        }

        public function edit() {
            echo "WallController edit method"; 
        }

        public function destroy() {
            echo "WallController destroy method"; 
        }

    }
    