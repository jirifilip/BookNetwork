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
            
            public function isSubbed($userId, $bookId) {
                $res = Db::queryAll(
                    "SELECT * FROM $this->name WHERE book_id = ? AND user_id = ?",
                    [$bookId, $userId]
                );

                if (count($res) > 0) {
                    return true;
                } else {
                    return false;
                }
            }

            public function allUsers() {
                $result = Db::queryAll('SELECT * FROM user');
            }

            public function allBooks() {

            } 

            public function getSubscribedTo($id) {
                $res = Db::queryAll(
                    "SELECT
                        book.name as book_name,
                        book.description,
                        book.url as book_url,
                        book.picture_url,
                        book.id as book_id,
                        story.id as story_id,
                        story.name as story_name,
                        story.url as story_url,
                        story.created_at as story_created_at,
                        user.username
                    FROM sub_book
                    INNER JOIN book
                    ON (sub_book.book_id = book.id)
                    INNER JOIN story
                    ON (story.book_id = book.id)
                    INNER JOIN user
                    ON (book.author_id = user.id)
                    WHERE sub_book.user_id=?
                    ORDER BY story.created_at DESC"
                , [$id]);

                return $res;
            }

    }
    