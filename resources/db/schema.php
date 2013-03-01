<?php

/**
 * @author Саша Стаменковић <umpirsky@gmail.com>
 * @author Didier Belot <electrolinux@gmail.com>
 */

$schema = new \Doctrine\DBAL\Schema\Schema();

/*
$post = $schema->createTable('post');
$post->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
$post->addColumn('title', 'string', array('length' => 32));
$post->setPrimaryKey(array('id'));
*/

$users = $schema->createTable('users');
$users->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
$users->setPrimaryKey(array('id'));
$users->addColumn('username', 'string', array('length' => 127));
$users->addUniqueIndex(array('username'),'username_idx');
$users->addColumn('password', 'string', array('length' => 255));
$users->addColumn('roles', 'string', array('length' => 255));
$users->addColumn('pastebin_api_key','string',array('length'=>32,'notNull'=>false));

$snippet = $schema->createTable('snippet');
$snippet->addColumn('id','integer',array('unsigned'=>true,'autoincrement'=>true));
$snippet->setPrimaryKey(array('id'));
$snippet->addColumn('name','string',array('length'=>50));
$snippet->addColumn('interp', 'string',array('length'=>25, 'default'=> 'php'));
$snippet->addColumn('code', 'text');
$snippet->addColumn('rows', 'integer');
$snippet->addColumn('level', 'integer',array('default'=>0));
$snippet->addColumn('comment', 'text',array('notNull' => false));
$snippet->addUniqueIndex(array('name','interp'),'nameinterp_idx');

return $schema;
