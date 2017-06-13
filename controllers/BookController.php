<?php

    class BookController extends Controller {

        private $book;
        private $subscription;
        private $user;
        private $genre;

        function __construct() {
            $this->book = new Book();
            $this->user = new User();
            $this->subscription = new Subscription();
            $this->genre = new Genre();

            $this->pushGuard( new BookAuthorGuard );
            $this->pushGuard( new NewBookGuard );
        }
    
        public function index() {
            $books = $this->book->all();

            if (!is_array(reset($books))) {
                $books = [$books];
            }

            $this->render("books.index", [
                "books" => $books,
                "title" => "Přehled knih"
            ]);
        }

        public function create() {
            if (!Session::exists('id')) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "K vytvoření povídky se musíš přihlást"
                ]);
                exit();
            }

            $this->render("books.create", [
                "title" => "Nová kniha"
            ]);
        }

        public function show() {
            $id = $this->params['id'];
            
            $stories = $this->book->bookAndStory($id);

            $singleBook = $this->book->where('id', "=", $id)->apply();

            $genres = $this->genre->forBook($id);

            if (empty($singleBook)) {
                $this->error([
                    "title" => "Stránka nenalezena",
                    "error" => "Takováto kniha neexistuje"
                ]);

                exit();
            }

            if (Session::exists('fb_id')) {
                $logged = true;
                $subbed = $this->subscription->isSubbed(Session::get('id'), $id);
            } else {
                $logged = false;
                $subbed = false;
            }

            $this->render("books.show", [
                "title" => $singleBook['name'],
                "book" => $singleBook,
                "genres" => $genres,
                "stories" => $stories,
                "subbed" => $subbed,
                "logged" => $logged,
                "isAuthor" =>
                    ($singleBook['author_id'] == Session::safeGet('id')) || (Session::safeGet('admin') == 1) 
            ]);
        }

        public function store() {
            if (!Session::exists('id')) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "K vytvoření povídky se musíš přihlást"
                ]);
                exit();
            }

            $name = @Post::get('name');
            $desc = @Post::get("description");
            $pic_url = @Post::get("picture_url");
            $access = @Post::get("access_everyone");
            $author_id = Session::get('id');
            $url = URLUtils::slugify($name);

            $errors = $this->book->validate([
                "name" => $name,
                "picture_url" => $pic_url,
                "description" => $desc,
                "access_everyone" => $access
            ]);

            if (!empty($errors)) {
                $book = [
                    "name" => $name,
                    "description" => $desc,
                    "picture_url" => $pic_url,
                    "access_everyone" => $access
                ];

                $this->render("books.create", [
                    "title" => "Vytvoření knihy",
                    "errors" => $errors,
                    "book" => $book
                ]);

                exit();
            } 


            $this->book->insert([
                "name" => $name,
                "description" => $desc,
                "picture_url" => $pic_url,
                "author_id" => $author_id,
                "url" => $url
            ]);

            UrlUtils::redirect("/".BASE_URL."/knihy");

        }

        public function edit() {
            $id = @Post::get('id');
            $name = @Post::get('name');
            $desc = @Post::get("description");
            $updatedAt = @Post::get('updated_at');
            $access = @Post::get("access_everyone");
            $pic_url = @Post::get("picture_url");
            $author_id = Session::get('id');
            $url = URLUtils::slugify($name);

            if ($updatedAt > date("Y-m-d H:i:s")) {
                $this->error([
                    "title" => "Chyba",
                    "error" => 'Prosím nepokoušejte se o útok na naši stránku. Děkujeme.'
                ]);
                exit();
            }

            $errors = $this->book->validate([
                "name" => $name,
                "picture_url" => $pic_url,
                "description" => $desc,
                "access_everyone" => $access
            ]);

            if (!empty($errors)) {
                $book = [
                    "id" => $id,
                    "name" => $name,
                    "description" => $desc,
                    "picture_url" => $pic_url,
                    "updated_at" => $updatedAt,
                    "access_everyone" => $access
                ];

                $this->render("books.edit", [
                    "title" => "Vytvoření knihy",
                    "errors" => $errors,
                    "book" => $book
                ]);

                exit();
            }

            $oldBook = $this->book->where('id', '=', $id)->apply();

            if ($oldBook['updated_at'] > $updatedAt) {
                Post::set('lock', true);

                $book = [
                    "id" => $id,
                    "name" => $name,
                    "description" => $desc,
                    "picture_url" => $pic_url,
                    "updated_at" => date("Y-m-d H:i:s"),
                    "access_everyone" => $access
                ];

                $this->render("books.edit", [
                    "title" => "Vytvoření knihy",
                    "errors" => $errors,
                    "book" => $book,
                    "oldBook" => $oldBook
                ]);

                exit();
            }

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

            UrlUtils::redirect("/".BASE_URL."/knihy"); 
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
            $id = Post::get('book_id');

            $this->book->delete(['id', $id]);

            UrlUtils::redirect("/".BASE_URL."/knihy");
        }

        public function showByAuthor() {
            $username = $this->params["username"];
            
            $user = $this->user->where('username', "=", $username)->apply();
            
            if (empty($user)) {
                $this->error([
                    "title" => "Stránka nenalezena",
                    "error" => "Takovýto uživatel neexistuje"
                ]);

                exit();
            }
            
            $id = $user['id'];
            // ošetřit nenalezení

            $books = $this->book->where('author_id', '=', $id)->apply();

            if (!is_array(reset($books))) {
                $books = [$books];
            }

            $this->render('books.showByAuthor', [
                "books" => $books,
                "username" => $username,
                "title" => "Přehled knih od autora $username"
            ]);
        }

    }
    