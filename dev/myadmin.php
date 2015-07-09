<?php

/**
 * adminer extension for default
 * host, username and password to use it under docker
 * environment for php/mysql projects mainly.
 */
function adminer_object()
{
    class AdminerSoftware extends Adminer
    {
        public function name()
        {
            // custom name in title and heading
            return 'MyAdminer';
        }

        public function permanentLogin()
        {
            // key used for permanent login
            return "cdf1c4318ce97b64954c045b9a67ad6c";
        }

        public function credentials()
        {
            // server, username and password for connecting to database
            return array('db', 'root', 'root');
        }
    }

    return new AdminerSoftware;
}

include './adminer.php';
