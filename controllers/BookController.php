<?php

    class BookController extends Controller {

        private $book;
        private $user;

        function __construct() {
            $this->book = new Book();
            $this->user = new User();
        }
    
        public function index() {
            $books = $this->book->all();

            $this->render("books.index", [
                "books" => $books,
                "title" => "Přehled knih"
            ]);
        }

        public function create() {
            $this->render("books.create", [
                "title" => "Nová kniha"
            ]);
        }

        public function show() {
            $id = $this->params['id'];
            
            $book = $this->book->where('id', "=", $id)->apply(false);

            $this->render("books.show", [
                "title" => $book['name'],
                "book" => $book,
                "stories" => $book['story']
            ]);
        }

        public function store() {

            $name = Post::get('name');
            $desc = Post::get("description");
            $pic_url = Post::get("picture_url");
            $author_id = Session::get('id');
            $url = URLUtils::slugify($name);

            $this->book->update([
                "name" => $name,
                "description" => $desc,
                "picture_url" => $pic_url,
                "author_id" => $author_id,
                "url" => $url
            ]);

            echo "Kniha vytvořena";
        }

        public function edit() {
            $id = Post::get('id');
            $name = Post::get('name');
            $desc = Post::get("description");
            $pic_url = Post::get("picture_url");
            $author_id = Session::get('id');
            $url = URLUtils::slugify($name);

            $this->book->update(
                ["id", $id],
                [
                    "name" => $name,
                    "description" => $desc,
                    "picture_url" => $pic_url,
                    "author_id" => $author_id,
                    "url" => $url
                ]
            );

            echo "Kniha upravena"; 
        }

        public function load() {
            $id = $this->params['id'];

            $book = $this->book->where('id', "=", $id)->apply();

            $this->render('books.edit', [
                "title" => "Upravit",
                "book" => $book
            ]);
        }

        public function destroy() {
            $id = $this->params['id'];

            $this->book->delete(['id', $id]);

            echo "Kniha smazána";
        }

        public function showByAuthor() {
            $username = $this->params["username"];
            
            $user = $this->user->where('username', "=", $username)->apply();
            $id = $user['id'];
            // ošetřit nenalezení

            $books = $this->book->where('author_id', '=', $id)->apply();

            $this->render('books.index', [
                "books" => $books,
                "title" => "Přehled knih od autora $username"
            ]);
        }

    }
    