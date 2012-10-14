<?php

/**
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */

require_once __DIR__.'/../../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__.'/../config/dev.php';
require __DIR__.'/../../src/app.php';

$schema = new \Doctrine\DBAL\Schema\Schema();
$post = $schema->createTable('post');
$post->addColumn('id', 'integer', array('unsigned' => true));
$post->addColumn('title', 'string', array('length' => 32));
$post->setPrimaryKey(array('id'));

foreach ($schema->toSql($app['db']->getDatabasePlatform()) as $sql) {
    echo $sql.';'.PHP_EOL;
}