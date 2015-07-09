<?php


require 'vendor/autoload.php';


$settings = [
    'DEBUG' => filter_var(getenv('DEBUG'), FILTER_VALIDATE_BOOLEAN),
    'MODE' => getenv('MODE') !== false ? getenv('MODE') : 'production',
    'database' => [
        'dsn' => 'mysql:host=db;dbname=newsletter;charset=utf8',
        'username' => 'root',
        'password' => 'root',
    ],
];


$app = new \Slim\App($settings);


/**
 * Fetch DI Container
 */
$container = $app->getContainer();

/**
 * register db
 */
$container['db'] = function ($c) {
    $_settings = $c['settings']['database'];
    return new \Slim\PDO\Database(
        $_settings['dsn'],
        $_settings['username'],
        $_settings['password']
    );
};

/**
 * register gearman
 */
$container['gclient'] = function ($c) {
    $client = new GearmanClient();
    $client->addServer('gearman', 4730);
    return $client;
};


// routes
$app->get('/', function ($request, $response) {

    $html = "<html><head></head><body style='width: 640px;margin:0 auto;'>
    <div><h3>Subscribe Newsletter</h3>
    <form name='newsletter' action='/save' method='post'>
    <label>Fullname: <input type='text' name='fullname' value=''/></label>
    <label>Email: <input type='email' name='email' value=''/></label>
    <input type='submit' value='Submit' name='submit' />
    </form></div></body></html>";

    $response->write($html);
    return $response;
})->setName('home');


$app->post('/save', function ($request, $response) {
    // save data here
    $fullname = $request->getParam('fullname');
    $email = $request->getParam('email');

    // primitive error checking
    if ($fullname == "" || $email == "") {
        return $response->withStatus(301)
                        ->withHeader('Location', '/');
    }

    // store data to db
    $datetime = new DateTime('NOW');
    $now = $datetime->format('Y-m-d H:i:s');

    $stmt = $this->db->insert(['fullname', 'email', 'created_on', 'updated_on'])
                    ->into('subscriber')
                    ->values([$fullname, $email, $now, $now]);
    $lastId = $stmt->execute();

    // again primitive handling
    if (!$lastId) {
        return $response->withStatus(301)
                        ->withHeader('Location', '/');
    }

    $html = "<html><head></head><body style='width: 640px;margin:0 auto;'>
    <div><h3>Subscribe Newsletter</h3>
    <p>Thank you %s, You are subscribed to our newsletter successfully.</p>
    <p><a href='/'>Go back </a></p>
    </div></body></html>";

    $html = sprintf($html, $fullname);

    $response->write($html);
    return $response;
});


$app->get('/run', function ($request, $response) {
    $query = $this->db->select()->from('subscriber')->limit(10);
    $stmt = $query->execute();

    foreach ($stmt->fetchAll() as $each) {
        $data = json_encode([
            'email' => $each['email'],
            'fullname' => $each['fullname'],
        ]);
        $this->gclient->addTaskBackground('send_mails', $data);
    }
    $this->gclient->runTasks();
});


// run application
$app->run();
