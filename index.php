<?php
mb_internal_encoding("UTF-8");

require('db\Db.php');
require('utils\Autoloader.php');
require_once __DIR__ . '/vendor/autoload.php';

Db::connect("localhost", "root", "", "short_stories");
Session::start();

RoutesConfig::run();
