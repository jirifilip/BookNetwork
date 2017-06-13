<?php
    
    class Subscription extends Model {
    
            function __construct() {
                $this->name = 'sub_book';
            }

            public function subscribe($userId, $bookId) {
                Db::queryAll(
                    'INSERT INTO $this->name (book_id, user_id) VALUES(?, ?)', 
                    [$bookId, $userId]
                );
            }

            public function allUsers() {
                $result = Db::queryAll('SELECT * FROM user');
            }

            public function allBooks() {

            } 

    }
    