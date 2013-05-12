<?php

use Symfony\Component\Validator\Constraints as Assert;
use Silex\Application;
use DA\Controller\IndexController;

require_once('DA/Controller/IndexController.php');
require_once('DA/Controller/LoginController.php');
require_once('DA/Controller/FormController.php');

function controller($shortName)
{
	//list($shortClass, $shortMethod) = explode('/', shortName, 2);

	return sprintf('DA\Controller\%s', $shortName);
}


$app->match('/', controller('IndexController::index'))->bind('homepage');
$app->match('/login', controller('LoginController::index'))->bind('login');
$app->match('/doctrine', controller('IndexController::doctrine'))->bind('doctrine');
$app->match('/form', controller('FormController::index'))->bind('form');

?>