<?php
    
    class Book extends Model {
    
            function __construct() {
                $this->name = 'book';
                $this->idColumnName = "id";

                $this->mToN("story", "story_in_book", "story_id", "story", "id", "book_id", "name");
            }

            public function validate(array $input) {
                $errors = [];

                if (strlen(@$input['name']) > 40) {
                    $errors['name'] = "Jméno nesmí být tak dlouhé!";
                }
                if (filter_var($input['picture_url'], FILTER_VALIDATE_URL) === false) {
                    $errors['picture_url'] = "Nesprávně zadané URL obrázku";
                }
                if (!in_array(@$input['access_everyone'], [0, 1])) {
                    $errors['access_everyone'] = "Vybrali jste špatnou hodnotu";
                }
                if (strlen(@$input["description"]) < 100) {
                    $errors['description'] = "Popis knihy musí být alespoň 100 znaků dlouhý";
                }

                return $errors;
            }

            public function allWithGenre() {
                $res = Db::queryAll(
                    "SELECT
                        book.id as book_id,
                        book.name as book_name,
                        book.picture_url as book_picture_url,
                        book.description as book_desc,
                        book.url as book_url,
                        book.upvotes as book_upvotes,
                        genre.name as genre_name,
                        book_is.id as book_is_id   
                     FROM book
                     LEFT JOIN book_is
                     ON (book.id = book_is.book_id)
                     LEFT JOIN genre
                     ON (book_is.genre_id = genre.id)"
                );

                return $res;
            }

            public function bookAndStory($bookId) {
                $res = Db::queryAll(
                    "SELECT
                        book.id as book_id,
                        book.name as book_name,
                        book.description as book_desc,
                        book.url as book_url,
                        book.upvotes as book_upvotes,
                        story.id as story_id,
                        story.name as story_name,
                        story.upvotes as story_upvotes,
                        story.url as story_url
                     FROM book
                     INNER JOIN story
                     ON (book.id = story.book_id)
                     WHERE book.id = ?",
                     [$bookId]
                );

                return $res;
            }

            public function all() {
                $res = Db::queryAll(
                    "SELECT
                        user.id as user_id,
                        user.fb_id,
                        user.email,
                        user.username,
                        user.profile_desc,
                        user.picture_url,
                        book.id as book_id,
                        book.name as book_name,
                        book.description as book_desc,
                        book.picture_url as book_picture_url,
                        book.url as book_url,
                        book.upvotes as book_upvotes
                     FROM book
                     INNER JOIN user
                     ON (book.author_id = user.id)"
                );

                return $res;
            }

    }
    