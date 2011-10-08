<?php

require_once __DIR__ . '/../vendor/silex/autoload.php';

use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SymfonyBridgesServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\DoctrineServiceProvider;

use SilexExtension\AsseticExtension;

$app = new Silex\Application();

require __DIR__ . '/config.php';

$app->register(new HttpCacheServiceProvider());

$app->register(new SessionServiceProvider());
$app->register(new SymfonyBridgesServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new UrlGeneratorServiceProvider());

$app->register(new TranslationServiceProvider(), array(
    'locale_fallback' => $app['locale'],
));
$app['translator.loader'] = new Symfony\Component\Translation\Loader\YamlFileLoader();


$app->register(new TwigServiceProvider(), array(
    'twig.options'  => array('cache' => false, 'strict_variables' => true),
    'twig.path'     => array(
        __DIR__ . '/../views/common',
        __DIR__ . '/../views',
    )
));

$oldTwigConfiguration = isset($app['twig.configure']) ? $app['twig.configure']: function(){};
$app['twig.configure'] = $app->protect(function($twig) use ($oldTwigConfiguration) {
    $oldTwigConfiguration($twig);
    $twig->addExtension(new Twig_Extensions_Extension_Debug());
});

$app->register(new DoctrineServiceProvider(), array(
    'db.options'    => array(
        'driver'    => $app['db.config.driver'],
        'dbname'    => $app['db.config.dbname'],
        'host'      => $app['db.config.host'],
        'user'      => $app['db.config.user'],
        'password'  => $app['db.config.password'],
    ),
    'db.dbal.class_path'    => __DIR__ . '/../vendor/silex/vendor/doctrine-dbal/lib',
    'db.common.class_path'  => __DIR__ . '/../vendor/silex/vendor/doctrine-common/lib',
));

$app->register(new AsseticExtension(), array(
    'assetic.options' => array(
        'debug' => $app['debug']
    ),
    'assetic.filters' => $app->protect(function($fm) use ($app) {
        $fm->set('yui_css', new Assetic\Filter\Yui\CssCompressorFilter(
            $app['assetic.filter.yui_compressor.path']
        ));
        $fm->set('yui_js', new Assetic\Filter\Yui\JsCompressorFilter(
            $app['assetic.filter.yui_compressor.path']
        ));
    }),
    'assetic.assets' => $app->protect(function($am, $fm) use ($app) {
        $am->set('styles', new Assetic\Asset\AssetCache(
            new Assetic\Asset\GlobAsset(
                $app['assetic.input.path_to_css'],
                // Yui compressor is disabled by default.
                // If you need it, and you have installed it, uncomment the
                // next line, and delete "array()"
                //array($fm->get('yui_css'))
                array()
            ),
            new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
        ));
        $am->get('styles')->setTargetPath($app['assetic.output.path_to_css']);

        $am->set('scripts', new Assetic\Asset\AssetCache(
            new Assetic\Asset\GlobAsset(
                $app['assetic.input.path_to_js'],
                // Yui compressor is disabled by default.
                // If you need it, and you have installed it, uncomment the
                // next line, and delete "array()"
                //array($fm->get('yui_js'))
                array()
            ),
            new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
        ));
        $am->get('scripts')->setTargetPath($app['assetic.output.path_to_js']);
    })
));

return $app;
