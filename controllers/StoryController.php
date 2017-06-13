<?php

    class StoryController extends Controller {

        function __construct() {
            $this->story = new Story();
    
            $this->pushGuard( new AccessEveryoneGuard );
            $this->pushGuard( new StoryAuthorGuard );
        }
    
        public function index() {

            $stories = $this->story->all();

            $this->render('stories.index', [
                "title" => "Povídky",
                "stories" => $stories
            ]); 
        }

        public function create() {
            if (!Session::exists("id")) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "Musíte být přihlášen"
                ]);
                exit();
            }

            $this->render('stories.create', [
                "title" => "Vytvořit povídku",
                "bookId" => $this->params['bookId']
            ]);
        }

        public function show() {
            $url = $this->params['url'];
            $id = $this->params['id'];

            $story = $this->story->where("id", "=", $id)->apply(false);

            if (empty($story)) {
                $this->error([
                    "title" => "Stránka nenalezena",
                    "error" => "Takováto povídka neexistuje"
                ]);

                exit();
            }

            $this->render("stories.show", [
                "title" => $story['name'],
                "comments" => $story['comment'],
                "story" => $story
            ]);
            
        }

        public function store() {
            $name = @Post::get('name');
            $bookId = @Post::get('book_id');
            $text = @Post::get('text');
            $url = URLUtils::slugify($name);

            if (!Session::exists("id")) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "Musíte být přihlášen"
                ]);
                exit();
            }

            if (!is_numeric($bookId)) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "ID musí být číslo"
                ]);
                exit();
            }

            $errors = $this->story->validate([
                "name" => $name,
                "text" => $text
            ]);

            if (!empty($errors)) {
                $this->render('stories.create', [
                    "title" => "Vytvořit povídku",
                    "bookId" => $this->params['bookId'],
                    "story" => [
                        "name" => $name,
                        "text" => $text
                    ],
                    "bookId" => $bookId,
                    "errors" => $errors
                ]);

                exit();
            }

            $this->story->insert([
                "name" => $name,
                "text" => $text,
                "url" => $url,
                "book_id" => $bookId
            ]);

            URLUtils::redirect("/".BASE_URL."/knihy/".$bookId."/name");
        }

        public function load() {
            $id = $this->params['id'];

            $story = $this->story->where('id', '=', $id)->apply();

            if (empty($story)) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "Taková povídka neexistuje"
                ]);
                exit();
            }

            $this->render('stories.edit', [
                "title" => $story['name'],
                "story" => $story
            ]);
        }

        public function edit() {
            $id = @Post::get('id');
            $name = @Post::get('name');
            $text = @Post::get('text');
            $updatedAt = @Post::get('updated_at');
            $url = URLUtils::slugify($name);

            if (!is_numeric($id)) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "ID musí být číslo"
                ]);
                exit();
            }

            $errors = $this->story->validate([
                "name" => $name,
                "text" => $text
            ]);


            if (!empty($errors)) {
                $story = [
                    "name" => $name,
                    "text" => $text,
                    "id" => $id
                ];

                $this->render('stories.edit', [
                    "title" => "Upravit povídku",
                    "errors" => $errors,
                    "story" => $story
                ]);

                exit();
            }

            $oldStory = $this->story->where('id', '=', $id)->apply();

            if ($oldStory['updated_at'] > $updatedAt) {
                Post::set('lock', true);

                $story = [
                    "id" => $id,
                    "name" => $name,
                    "text" => $text,
                    "updated_at" => date("Y-m-d H:i:s"),
                ];
                
                $this->render('stories.edit', [
                    "title" => $name,
                    "story" => $story,
                    "oldStory" => $oldStory
                ]);

                exit();
            }
            
            $this->story->update(
                ["id", $id],
                [
                    "name" => $name,
                    "text" => $text
                ]
            );

            URLUtils::redirect("/".BASE_URL."/povidky/$id/$url");
        }

        public function destroy() {
            $id = @Post::get('id');

            if (!is_numeric($id)) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "ID musí být číslo"
                ]);
                exit();
            }

            $this->story->delete(['id', $id]);

            URLUtils::previousPage();
        }

    }
    