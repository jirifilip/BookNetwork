<?php

class RoutesConfig {

    /*
    ** Tady nastavit routes
    */
    public static function run() {
        Router::push("", "HomeController@index");

        Router::pushController("povidky", "StoryController", array(
            "create" => "nova",
            "destroy" => "smazat",
            "edit" => "upravit"
        ), [
            "id",
            "url"
        ]);

        Router::pushController("zed", "WallController");

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

        Router::push("profil", "ProfileController@index");


        Router::pushController("bla", "BlaController", array(
            "create" => "vytvorit",
            "destroy" => "smazat",
            "edit" => "upravit"
        ), [
            "id",
            "url"
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




        Router::api("upvote", "UpvoteAPI");
        Router::api("subscription", "SubscriptionAPI");

        // Router::log();

        Router::run();
        
    }


}