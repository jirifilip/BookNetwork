<?php

    class CommentController extends Controller {

        function __construct() {
            $this->comment = new Comment();
	    $this->story = new Story();

            $this->pushGuard(
                new NoRouteGuard(["index", "create", "edit", "load", "destroy"])
            );
        }
    
        public function show() {
            $id = $this->params['id-story'];

            $stories = $this->story->where('id', '=', $id);

            if (empty($stories)) {
                $this->error([
                    "title" => "Stránka nenalezena",
                    "error" => "Takováto povídka neexistuje"
                ]);

                exit();
            }

	    $comments = $this->comment->forStory($id);

            if (!is_array(reset($comments))) {
                $comments = [$comments];
            }

            $this->render("comments.show", [
                "id" => $id,
                "comments" => $comments,
                "title" => "Komentáře k povídce"
            ]);
        }

        public function store() {
            $storyId = @Post::get("storyId");

            if (!Session::exists('id')) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "K této akci se musíte přihlásit."
                ]);
                exit();
            }

            if (!is_numeric($storyId)) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "Id povídky musí být číslo."
                ]);
                exit();
            }

            $text = @Post::get("text");

            $errors = $this->comment->validate([
                "text" => $text
            ]);

            if (!empty($errors)) {
                $comments = $this->comment->forStory($storyId);

                $this->render("comments.show", [
                    "id" => $storyId,
                    "text" => $text,
                    "comments" => $comments,
                    "title" => "Komentáře k povídce",
                    "errors" => $errors
                ]);

                exit();
            }

            $this->comment->insert([
                "text" => $text,
                "story_id" => $storyId,
                "user_id" => Session::get('username'),
                "upvotes" => 0
            ]);

            UrlUtils::previousPage();
        }

    }
    