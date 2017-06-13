<?php

class Validator {

    static public function isInteger($text) {
        return preg_match("/\d+/", $text);
    }

    static public function isWord($text) {
        return preg_match("/$\w+/", $text);
    }

    static public function isName($text) {
        return preg_match("/^(( |-|\p{L})+)$/", $text) == true;
    }

    static public function isUrl($text) {
        $matches = [];
        preg_match("/^((\w|:|\/|.|&|=|:|\?|%|_)+)$/", $text, $matches);
        die(var_dump($matches));
        return (!empty($matches));
    }

    static public function isWordWithLength($length, $text) {
        return preg_match("/\w{1,$length}/", $text);
    }

}