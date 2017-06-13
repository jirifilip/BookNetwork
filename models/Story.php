<?php
    
class Story extends Model {

    function __construct() {
        $this->name = 'story';
        $this->idColumnName = "id";

        $this->hasOne("comment", "story_id");
    }

    public function validate(array $input) {
        $errors = [];

        if (strlen(@$input['name']) < 3) {
            $errors['name'] = "Nesprávně zadaný název. Musí být delší než tři znaky";
        }
        if (strlen(@$input["text"]) < 500) {
            $errors['text'] = "Text kapitoly musí být alespoň 500 znaků dlouhý";
        }

        return $errors;
    }

    public function fromAuthor($authorId) {
        $res = Db::queryAll(
            "SELECT * FROM story
            INNER JOIN book
            ON (story.book_id = book.id)
            INNER JOIN user
            ON (book.author_id = user.id)
            WHERE user.id = ?",
            [$authorId]
        );

        return $res;
    }

}
    