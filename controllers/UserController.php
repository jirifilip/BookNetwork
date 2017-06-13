<?php

    class UserController extends Controller {

        function __construct() {
            $this->user = new User();
            $this->friend = new Friend();

            $this->pushGuard(
                new NoRouteGuard(["create", "store", "edit", "destroy"])
            );
        }
    
        public function index() {
            $users = $this->user->all();

            $this->render('user.index', [
                "title" => "Uživatelé",
                "users" => $users
            ]);
        }

        public function show() {
            $username = $this->params['username'];

            if ($username == @Session::get('username')) {
                UrlUtils::redirect("/".BASE_URL."/zed");
            }

            $user = $this->user->getFullProfile($username);

            if (empty(reset($user))) {
                $this->error([
                    "title" => "Stránka nenalezena",
                    "error" => "Takovýto uživatel neexistuje"
                ]);

                exit();
            }

            $id = $user['id'];

            $user['friends'] = $this->friend->getFriends($id);

            $this->render("user.show", [
                "title" => "Uživatel " . $user['username'],
                "profile" => $user
            ]);
        }

    }
    