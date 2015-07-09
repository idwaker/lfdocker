<?php


require 'vendor/autoload.php';


$app = new \Slim\App;

// settings


// routes
$app->get('/', function ($req, $resp) {
    $resp->write("This is home page");
    return $resp;
})->setName('home');


// run application
$app->run();
