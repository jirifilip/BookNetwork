<?php

    class UserController extends Controller {

        function __construct() {
            $this->user = new User();
        }
    
        public function index() {
            $users = $this->user->all();

            $this->render('user.index', [
                "title" => "Uživatelé",
                "users" => $users
            ]);
        }

        public function create() {
            echo "UserController create method"; 
        }

        public function show() {
            $username = $this->params['username'];

            $user = $this->user->getFullProfile($username);

            $this->render("wall.index", [
                "title" => "Uživatel " . $user['username'],
                "profile" => $user
            ]);
        }

        public function store() {
            echo "UserController store method"; 
        }

        public function edit() {
            echo "UserController edit method"; 
        }

        public function destroy() {
            echo "UserController destroy method"; 
        }

    }
    