<?php

    class FriendController extends Controller {

        private $friend;

        function __construct() {
            $this->friend = new Friend();

            $this->pushGuard( new LoggedInGuard );

            $this->pushGuard( new NoRouteGuard(["create", "show", "load"]) );
        }
    
        public function index() {
            $id = Session::get('id');

            $requests = $this->friend->getRequests($id);
            $friends = $this->friend->getFriends($id);

            if (!is_array(reset($requests))) {
                $requests = [$requests];
            }

            $this->render('friends.index', [
                "title" => "Přátelé",
                "requests" => $requests,
                "friends" => $friends
            ]);
        }

        public function store() {
            $friendId = @Post::get('friendId');

            $myId = Session::get('id');

            if (!is_numeric($friendId)) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "Špatně vybraný přítel"
                ]);
                exit();
            }

            if ($friendId == $myId) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "Nemůžeš odeslat žádost o přátelství sám sobě!"
                ]);
                exit();
            }

            if ($this->friend->alreadyFriends($myId, $friendId)) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "Už jste přáteli!"
                ]);
                exit();
            }

            $friends = $this->friend->insert([
                "user_id1" => $myId,
                "user_id2" => $friendId,
                "pending" => 1
            ]);

	    URLUtils::previousPage();

        }

        public function edit() {
            $friendshipId = @Post::get('friendship_id');

            if (!is_numeric($friendshipId)) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "Špatně zadané id"
                ]);
                exit();
            }

            $this->friend->update(
                ['friendship_id', $friendshipId],
                ["pending" => 0]
            );

            URLUtils::previousPage();
        }

        public function destroy() {
            $friendshipId = @Post::get('friendship_id');

            if (!is_numeric($friendshipId)) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "Špatně zadané id"
                ]);
                exit();
            }

            $this->friend->delete(['friendship_id', $friendshipId]);

            URLUtils::previousPage();
        }

    }
    