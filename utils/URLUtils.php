<?php

class URLUtils {

    //sem přijde jméno domény
    public static $domain = "semestralni_prace";

    static function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    static function getURL() {
        return $_SERVER['REQUEST_URI'];
    }

    static function parseURL() {
        return explode( "/", self::getURL() );
    }

    static function redirect($location) {
        header("Location: $location");
    }

    static function localhostSpecificURL() {
        $urlArray = self::parseURL();
        $index = self::domainIndex();
        $indexToSlice = -count($urlArray) + 2 + $index;
        $finalArray = array_slice( $urlArray, $indexToSlice);

        return $finalArray;
    }

    static function domainIndex() {
        $urlArray = self::parseURL();
        $index = array_search(self::$domain, $urlArray);
        return $index;
    }

    static function controllerName() {
        return self::localhostSpecificURL()[0];
    }


    static function method($name) {
        return self::getMethod() == $name;
    }

    static public function slugify($text) {

        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        $text = preg_replace('~[^-\w]+~', '', $text);

        $text = trim($text, '-');

        $text = preg_replace('~-+~', '-', $text);

        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

}