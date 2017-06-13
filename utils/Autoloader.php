<?php

function autoLoader($className) {
    if (preg_match("/Controller/", $className)) {
        require_once("controllers/$className.php");
    }
    else if (preg_match("/Utils/", $className)) {
        require_once("utils/$className.php");
    }
    else if (preg_match("/Guard/", $className)) {
        require_once("guards/$className.php");
    }
    else if (preg_match("/Form/", $className)) {
        require_once("forms/$className.php");
    }
    else if (preg_match("/(REST|API)/", $className)) {
        require_once("api/$className.php");
    }
    else {
        if (is_file("models/$className.php")) {
            require_once("models/$className.php");
        }
        else if (is_file("routing/$className.php")) {
            require_once("routing/$className.php");
        }
        else if (is_file("utils/$className.php")) {
            require_once("utils/$className.php");
        }
        else {
            require_once("$className.php");
        }
    }
}

function partial($name) {
    require("views/$name.phtml");
}

spl_autoload_register('autoLoader');