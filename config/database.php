<?php

use Dotenv\Dotenv;

return [
    'dbconn' => $_ENV['dbconn'],
    'dbhost' => $_ENV['dbhost'],
    'dbport' => $_ENV['dbport'],
    'dbuser' => $_ENV['dbuser'],
    'dbpass' => $_ENV['dbpass'],
    'dbname' => $_ENV['dbname'],
];
