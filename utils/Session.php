<?php

class Session {

    private function __construct() {

    }

    public static function start() {
        session_start();
    }

    public static function destroy() {
        session_destroy();
    }

    public static function get($key) {
        return $_SESSION[$key];
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function all() {
        return $_SESSION;
    }

    public static function exists($key) {
        return isset($_SESSION[$key]);
    }

    public static function isEmpty() {
        return empty($_SESSION);
    }

}