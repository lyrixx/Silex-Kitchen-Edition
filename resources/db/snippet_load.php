<?php

/**
 * @author didier Belot <electrolinux@gmail.com>
 */
namespace Oclane;

use Symfony\Component\Finder\Finder;

use Doctrine\DBAL\Schema\Table;

$schema = $app['db']->getSchemaManager();
if (!$schema->tablesExist('snippet')) {
    throw new \Exception("table snippet don't exists !!");
}
$db = $app['db'];
$db->executeQuery('DELETE FROM snippet');

$finder = new Finder();
$finder->files()
    ->ignoreVCS(true)
    ->name('*.txt')
    ->notName('*~')
    ->in(__DIR__.'/snippets')
;
$php = new Snippet($db);
$sql = new SnippetSql($db);
$js = new SnippetJs($db);

foreach ($finder as $file) {
    $name = str_replace('.txt','',basename($file));
    $interp = basename(dirname($file));
    $code = file_get_contents($file);
    echo "$interp: $name\n";
    if ($interp == 'php') {
        $php->add($name,$code);
    } elseif ($interp == 'sql') {
        $sql->add($name,$code);
    } elseif ($interp = 'js') {
        $js->add($name,$code);
    }
}
