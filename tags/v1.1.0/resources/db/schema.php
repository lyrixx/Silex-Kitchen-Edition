<?php

/**
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */

$schema = new \Doctrine\DBAL\Schema\Schema();

$post = $schema->createTable('post');
$post->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
$post->addColumn('title', 'string', array('length' => 32));
$post->setPrimaryKey(array('id'));

return $schema;
