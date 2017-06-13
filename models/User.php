<?php
    
    class User extends Model {

            public $subscription;
    
            function __construct() {
                $this->name = 'user';
                $this->subscription = new Subscription();
                $this->book = new Book();
            }


            public function insert($fbId, $username, $email, $picture) {
                Db::updateDelete("
                    INSERT INTO $this->name (fb_id, username, email, picture_url)
                    VALUES (?, ?, ?, ?);
                ", [$fbId, $username, $email, $picture]);
            }

            public function booksCount($id) {
                $res = Db::queryOne("
                    SELECT count(*) FROM book
                    WHERE author_id = ?;
                ", [$id]);
                
                return $res;
            }

            public function getFullProfile($username) {
                $profile = $this->where('username', "=", $username)->apply();
                $id = $profile["id"];

                $profile["subs"] = $this->subscription->where('user_id', "=", $id)->apply();

                $profile["books"] = $this->book->where("author_id", "=", $id)->apply();

                return $profile;
            }

    }
    