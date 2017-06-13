<?php
    
    class Book extends Model {
    
            function __construct() {
                $this->name = 'book';
                $this->idColumnName = "id";

                $this->mToN("story", "story_in_book", "story_id", "story", "id", "book_id", "name");
            }

    }
    