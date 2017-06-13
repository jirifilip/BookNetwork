<?php

    class SubscriptionController extends Controller {

        private $subscription;

        function __construct() {
            $this->subscription = new Subscription();

            $this->pushGuard( new LoggedInGuard );

            $this->pushGuard( new NoRouteGuard(['show', 'create', 'load', 'edit']) );
        }
    
        public function index() {

            $id = Session::get('id');

            $subs = $this->subscription->getSubscribedTo($id);


            $this->render("subscription.index", [
                "title" => "Odběry",
                "subs" => $subs
            ]);
        }

        public function store() {
            $bookId = @Post::get('bookId'); 
            $userId = Session::get('id');

            if (!is_numeric($bookId)) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "Id knihy musí být číslo!"
                ]);
                exit();
            }

            $sub = $this->subscription
                ->where("user_id", "=", $userId)
                ->where("book_id", "=", $bookId)
                ->apply();

            if (!empty($sub)) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "Tuto knihu již máte v odběru!"
                ]);
                exit();
            }

            $this->subscription->insert([
                "book_id" => $bookId,
                "user_id" => $userId
            ]);

            UrlUtils::previousPage();
        }

        public function destroy() {
            $bookId = @Post::get('bookId'); 
            $userId = Session::get('id');

            if (!is_numeric($bookId)) {
                die("Id knihy musí být číslo!");
            }

            $sub = $this->subscription
                ->where("user_id", "=", $userId)
                ->where("book_id", "=", $bookId)
                ->apply();

            if (empty($sub)) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "Tuto knihu nemáte v odběru!"
                ]);
                exit();
            }

            $this->subscription->delete([
                ["book_id", $bookId],
                ["user_id", $userId]
            ]);

            UrlUtils::previousPage();
        }

    }
    