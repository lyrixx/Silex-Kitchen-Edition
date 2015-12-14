<?php

require_once __DIR__.'/../vendor/autoload.php';

Symfony\Component\Debug\Debug::enable();

$app = new App\Application('dev');
$app->run();
