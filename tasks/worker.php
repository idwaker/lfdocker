<?php



$worker = new GearmanWorker();
$worker->addServer('gearman', 4730);

$fp = fopen('/var/www/dummy.log', 'a');
fwrite($fp, "Writing some dummy logs ............\n");


$worker->addFunction("send_mails", function ($job) use ($fp) {
    $arguments = json_decode($job->workload(), true);

    if (!empty($arguments['email'])) {
        // just dummy mail function which prints to stdout
        $msg = "Sending mail to ... " . $arguments['email'] . "\n";
        fwrite($fp, $msg);
    }
});

while (true) {
    $worker->work();
}
