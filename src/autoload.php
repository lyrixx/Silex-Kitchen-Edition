<?php

require_once __DIR__.'/../vendor/silex/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'           => array(__DIR__.'/../vendor/silex/vendor', __DIR__.'/../vendor'),
    'Silex'             => __DIR__.'/../vendor/silex/src',
    'SilexExtension'    => __DIR__.'/../vendor/Silex-extentions/fate/src',
    'Assetic'           => __DIR__.'/../vendor/assetic/src',
));
$loader->registerPrefixes(array(
    'Pimple' => __DIR__.'/../vendor/silex/vendor/pimple/lib',
    'Twig_'  => array(__DIR__.'/../vendor/silex/vendor/twig/lib', __DIR__.'/../vendor/Twig-extentions/Fabpot/lib/'),
));
$loader->register();
