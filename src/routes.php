<?php

use Symfony\Component\Validator\Constraints as Assert;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once('DA/Controller/IndexController.php');
require_once('DA/Controller/LoginController.php');
require_once('DA/Controller/FormController.php');
require_once('DA/Controller/CacheController.php');
require_once('DA/Controller/ComponentController.php');
require_once('DA/Controller/AccountController.php');
require_once('DA/Controller/CommandController.php');

function controller($shortName)
{
	//list($shortClass, $shortMethod) = explode('/', shortName, 2);

	return sprintf('DA\Controller\%s', $shortName);
}


$app->match('/', controller('IndexController::index'))->bind('homepage');
$app->match('/login', controller('LoginController::index'))->bind('login');
$app->match('/account', controller('AccountController::index'))->bind('account');
$app->match('/logout', controller('LoginController::logout'))->bind('logout');
$app->match('/doctrine', controller('IndexController::doctrine'))->bind('doctrine');
$app->match('/form', controller('FormController::index'))->bind('form');
$app->match('/components', controller('ComponentController::index'))->bind('components');
$app->match('/cli/command', controller('CommandController::runcmd'))->bind('cli_submit');

if($app['debug']) {
    $app->match('/page-with-cache', controller('CacheController::index'))->bind('page-with-cache');
}

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
            $message.="CODE : $code";
            //$message.=print_r($app);
    }

    return new Response($message, $code);
});