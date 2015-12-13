<?php

// Doctrine: DB options
$app['db.options'] = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__.'/../db/database.dat',
);

// Security: User
$app['security.users'] = array('alice' => array('ROLE_USER', 'password'));
