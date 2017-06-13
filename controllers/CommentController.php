<?php

    class CommentController extends Controller {

        function __construct() {
            $this->comment = new Comment();
        }
    
        public function index() {
            echo "CommentController index method"; 
        }

        public function create() {
            echo "CommentController create method"; 
        }

        public function show() {
            $id = $this->params['id-story'];

            $comments = $this->comment->where("story_id", "=", $id)->apply();

            $this->render("comments.show", [
                "comments" => $comments,
                "title" => "Komentáře k povídce"
            ]); 
        }

        public function store() {
            echo "CommentController store method"; 
        }

        public function edit() {
            echo "CommentController edit method"; 
        }

        public function destroy() {
            echo "CommentController destroy method"; 
        }

    }
    