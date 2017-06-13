<?php

class RoutesConfig {

    /*
    ** Tady nastavit routes
    */
    public static function run() {
        Router::push("", "HomeController@index");


        //přepsat
        Router::pushController("povidky", "StoryController", array(
            "create" => "nova",
            "destroy" => "smazat",
            "edit" => "upravit"
        ), [
            "id",
            "url"
        ]);
        Router::push("povidky", "StoryController@create", ["bookId", "bookName"], "GET", "nova");
        Router::push("povidky", "StoryController@store", ["bookId", "bookName"], "POST", "nova");

        Router::push("zed", "WallController@index", array());
        Router::push("zed", "WallController@load", array(), "GET", "upravit");
        Router::push("zed", "WallController@edit", array(), "POST", "upravit");

        Router::pushController("komentare", "CommentController", array(
            "create" => "novy",
            "destroy" => "smazat",
            "edit" => "upravit"
        ), [
            "id-story",
            "url"
        ]);

        Router::pushController("uzivatele", "UserController", array(
            "create" => "novy",
            "destroy" => "smazat",
            "edit" => "upravit"
        ), [
            "username"
        ]);

        Router::pushController("zanr", "GenreController", [
            "create" => "pridat",
            "destroy" => "smazat",
            "edit" => "upravit"
        ]);

        Router::pushController("pratele", "FriendController", array(
            "create" => "pridat",
            "destroy" => "smazat",
            "edit" => "potvrdit"
        ), [
            "username"
        ]);

        Router::pushController("odznak", "AchievementController", [
            "create" => "vyzvednout",
            "destroy" => "smazat",
            "edit" => "upravit"
        ]);

        Router::pushController("upvote", "UpvoteController", [
            "destroy" => "do"
        ]);
        
        Router::pushController("odber", "SubscriptionController", [
            "create" => "novy",
            "destroy" => "zrusit",
            "edit" => "upravit"
        ]);

        
        // BookController
        Router::pushController("knihy", "BookController", array(
            "create" => "vytvorit",
            "destroy" => "smazat",
            "edit" => "upravit"
        ), [
            "id",
            "url"
        ]);
        Router::push("knihy", "BookController@showByAuthor", array("username"), "GET");

        Router::push("login", "LoginController@index", array());
        Router::push("login", "LoginController@destroy", array(), "GET", "odhlasit-se");
        Router::push("login", "LoginController@loginCallback", array(), "GET", "create\?.+");
        Router::push("login", "LoginController@create", array(), "GET", "vytvorit");
        Router::push("login", "LoginController@store", array(), "POST", "vytvorit");



        // Router::log();

        Router::run();
        
    }


}