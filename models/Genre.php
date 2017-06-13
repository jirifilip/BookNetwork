<?php
    
    class Genre extends Model {
    
            function __construct() {
                $this->name = 'genre';
            }

            public function forBook($bookId) {
                $res = Db::queryAll(
                    "SELECT
                        book.id as book_id,
                        book.name as book_name,
                        book.description as book_desc,
                        book.url as book_url,
                        book.upvotes as book_upvotes,
                        genre.name as genre_name,
                        book_is.id as book_is_id   
                     FROM book
                     LEFT JOIN book_is
                     ON (book.id = book_is.book_id)
                     LEFT JOIN genre
                     ON (book_is.genre_id = genre.id)
                     WHERE book.id = ?",
                     [$bookId]
                );

                return $res;
            }
    }
    