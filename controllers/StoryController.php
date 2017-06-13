<?php

    class StoryController extends Controller {

        function __construct() {
            $this->story = new Story();

            $this->creationForm = $this->story->creationForm();
        }
    
        public function index() {

            $stories = $this->story->all();

            $this->render('stories.index', [
                "title" => "Povídky",
                "stories" => $stories
            ]); 
        }

        public function create() {
            $form = $this->creationForm;
            
            $this->render('stories.create', [
                "title" => "Vytvořit povídku",
                "form" => $form
            ]);
        }

        public function show() {
            $url = $this->params['url'];
            $id = $this->params['id'];

            $story = $this->story->where("id", "=", $id)->apply(false);

            $this->render("stories.show", [
                "comments" => $story['comment'],
                "story" => $story
            ]);
            
        }

        public function store() {
            echo "StoryController store method"; 
        }

        public function load() {
            echo "load";
        }

        public function edit() {
            echo "StoryController edit method"; 
        }

        public function destroy() {
            echo "StoryController destroy method"; 
        }

    }
    