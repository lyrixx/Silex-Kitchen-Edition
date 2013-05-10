<?php

$schema = new \Doctrine\DBAL\Schema\Schema();

$post = $schema->createTable('post');
$post->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
$post->addColumn('title', 'string', array('length' => 32));
$post->setPrimaryKey(array('id'));

$users = $schema->createTable('users');
$users->addColumn('user_id','integer',array('unsigned' => true, 'autoincrement' => true));
$users->addColumn('username','string',array('length' => 20));
$users->addColumn('password','string',array('length' => 32));
$users->addColumn('active','string',array('length'=>1));
$users->addColumn('role_id','integer',array('unsigned' => true));
$users->addColumn('register_date','date',array('default' => date('Y-m-d')));
$users->setPrimaryKey(array('user_id'));


return $schema;
