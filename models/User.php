<?php
    
    class User extends Model {

            public $subscription;
    
            function __construct() {
                $this->name = 'user';
                $this->subscription = new Subscription();
                $this->book = new Book();
            }


            public function insertInto($fbId, $username, $email, $picture) {
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

            public function validate($input) {
                $errors = [];

                if (strlen($input['description']) > 1500) {
                    $errors['description'] = "Popis profilu nesmí být delší než 1500 znaků";
                }
                if (filter_var($input['url'], FILTER_VALIDATE_URL) === false) {
                    $errors['url'] = "Špatně zadaná URL adresa";
                }

                return $errors;
            }

            public function getFullProfile($username) {
                $profile = $this->where('username', "=", $username)->apply();
                $id = @$profile["id"];

                $profile["subs"] = @$this->subscription->where('user_id', "=", $id)->apply();

                $books = @$this->book->where("author_id", "=", $id)->apply();
                
                if (@!is_array(array_shift(array_slice($books, 0, 1)))) {
                    $books = [$books];
                }

                $profile["books"] = $books;

                return $profile;
            }

    }
    