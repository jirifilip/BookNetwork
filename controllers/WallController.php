<?php

    class WallController extends Controller {

        private $user;
        private $friend;
        private $achv;

        function __construct() {
            $this->user = new User();
            $this->friend = new Friend();
            $this->achv = new Achievement();

            $this->pushGuard( new LoggedInGuard );

            $this->pushGuard(
                new NoRouteGuard(["create", "store", "show", "destroy"])
            );
        }
    
        public function index() {
            if (!Session::exists("username")) {
                URLUtils::redirect('/');
            }

            $username = Session::get('username');
            $user = $this->user->getFullProfile($username);

            $achvs = $this->achv->forUser(Session::get('id'));

            $user['friends'] = $this->friend->getFriends($user['id']);

            $this->render('wall.index', [
                "title" => "Zeď",
                "profile" => $user,
                "achvs" => $achvs
            ]);
        }

        public function load() {
            $id = Session::get("id");

            $user = $this->user->where("id", "=", $id)->apply();

            $user['friends'] = $this->friend->getFriends($id);

            $this->render('wall.edit', [
                "title" => "Profil - upravit",
                "profile" => $user
            ]);
        }

        public function edit() {
            
            $id = Session::get("id");

            $sentId = @Post::get("id");
            $description = @Post::get("description");
            $url = @Post::get("url");

            $errors = $this->user->validate([
                "description" => $description,
                "url" => $url
            ]);

            if ($id !== $sentId) {
                $this->error("Pokoušíte se upravit profil jiného uživatele!");
                exit();
            }

            if (!empty($errors)) {
                $this->render("wall.edit", [
                    "title" => "Profil - upravit",
                    "profile" => [
                        "id" => $id,
                        "profile_desc" => $description,
                        "picture_url" => $url
                    ],
                    "errors" => $errors
                ]);
                exit();
            }

            $this->user->update([
                "id", $id
            ], [
                "profile_desc" => $description,
                "picture_url" => $url
            ]);

            UrlUtils::redirect('/'.BASE_URL.'/zed');
        }

    }
    