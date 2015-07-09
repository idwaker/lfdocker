<?php


require 'vendor/autoload.php';


$settings = [
    'DEBUG' => filter_var(getenv('DEBUG'), FILTER_VALIDATE_BOOLEAN),
    'MODE' => getenv('MODE') !== false ? getenv('MODE') : 'production',
];

$app = new \Slim\App($settings);


// settings


// routes
$app->get('/', function ($req, $resp) {
    $html = "<html><head></head><body style='width: 640px;margin:0 auto;'>
    <div><h3>Subscribe Newsletter</h3>
    <form name='newsletter' action='/save' method='post'>
    <label>Fullname: <input type='text' name='fullname' value=''/></label>
    <label>Email: <input type='text' name='email' value=''/></label>
    <input type='submit' value='Submit' name='submit' />
    </form></div></body></html>";

    $resp->write($html);
    return $resp;
})->setName('home');


// run application
$app->run();
