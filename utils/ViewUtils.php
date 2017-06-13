<?php

class ViewUtils {

    public static function activeClass($class, $controllerName) {
        $class = URLUtils::controllerName() == $controllerName? $class : "";
        return $class;
    }

    public static function renderError($name, $errors) {
        echo "<div class='alert alert-danger'>$errors[$name]</div>";
    }

    public static function ifErrorRender($name, $errors) {
        if (!empty($errors[$name])) {
            self::renderError($name, $errors);
        }
    }
}