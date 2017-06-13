<?php

    class GenreController extends Controller {

        private $genre;
        private $book;
        private $bookIs;

        function __construct() {
            $this->genre = new Genre();
            $this->book = new Book();
            $this->bookIs = new BookIs();

            $this->pushGuard( new BookAuthorGuard );
            $this->pushGuard( new LoggedInGuard );
            
            $this->pushGuard( new NoRouteGuard(["create", "show", "store"]));
        }
    
        public function index() {
            $books = $this->book->allWithGenre();
            $genres = $this->genre->all();

            if (!is_array(reset($books))) {
                $books = [$books];
            }

            $this->render("genre.index", [
                "books" => $books,
                "title" => "Přehled knih podle žánru",
                "genres" => $genres
            ]);
        }

        public function load() {
            $id = $this->params['id'];

            $book = $this->book->where('id', "=", $id)->apply();

            $genreList = $this->genre->all();

            if (empty($book)) {
                die("Taková kniha neexistuje.");
            }

            $genres = $this->genre->forBook($id);

            $this->render("genre.edit", [
                "title" => "Upravit žánry",
                "genreList" => $genreList,
                "genres" => $genres
            ]); 
        }

        public function edit() {
            $bookId = @Post::get('book_id');
            $genreId = @Post::get('genre');

            if (!is_numeric($bookId) || !is_numeric($genreId) || !isset($bookId) || !isset($genreId)) {
               $this->error([
                    "title" => "Chyba",
                    "error" => "Takováto kniha nebo žánr neexistuje"
                ]);
                exit(); 
            }

            $book = $this->book->where('id', "=", $bookId);
            $genre = $this->genre->where("id", "=", $genreId);

            if (empty($book) || empty($genre)) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "Takováto kniha nebo žánr neexistuje"
                ]);
                exit();
            }

            if ($this->bookIs->isThere($bookId, $genreId)) {
                UrlUtils::previousPage();
            } else {
                $this->bookIs->insert([
                    "book_id" => $bookId,
                    "genre_id" => $genreId
                ]);

                URLUtils::previousPage();
            }
        }

        public function destroy() {
            $id = @Post::get('id');

            if (!is_numeric($id)) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "Takováto kombinace knihy a žánru neexistuje"
                ]);
                exit();
            }

            $this->bookIs->delete(['id', $id]);

            UrlUtils::previousPage(); 
        }

    }
    