<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/*
 * Common Pages
 */

$app->get('/',controller('IndexController::indexAction'))->bind('home');

$app->get('/about',controller('IndexController::aboutAction'))->bind('about');


