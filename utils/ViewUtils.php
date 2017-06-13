<?php

class ViewUtils {

    public static function activeClass($class, $controllerName) {
        $class = URLUtils::controllerName() == $controllerName? $class : "";
        return $class;
    }


}