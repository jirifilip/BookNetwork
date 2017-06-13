<?php
    
    class BookIs extends Model {
    
            function __construct() {
                $this->name = 'book_is';
            }

            public function isThere($bookId, $genreId) {
                $res = Db::queryAll(
                    "SELECT * FROM $this->name
                     WHERE book_id = ?
                     AND genre_id = ?",
                     [$bookId, $genreId]
                );

                if (!empty($res)) {
                    return true;
                } else {
                    return false;
                }
            }

    }
    