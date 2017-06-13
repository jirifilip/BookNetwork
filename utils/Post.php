<?php

class Post {

    public static function get($key) {
        return $_POST[$key];
    }

    public static function set($key, $value) {
        $_POST[$key] = $value;
    }

    public static function all() {
        return $_POST;
    }

    public static function exists($key) {
        return isset($_POST[$key]);
    }

    public static function isEmpty() {
        return empty($_POST);
    }

}