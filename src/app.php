<?php

use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

use Symfony\Component\Translation\Loader\YamlFileLoader;

use SilexAssetic\AsseticExtension;

$app->register(new HttpCacheServiceProvider());

$app->register(new SessionServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new UrlGeneratorServiceProvider());

$app->register(new TranslationServiceProvider(), array(
    'locale' => $app['locale'],
));
$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());

    $translator->addResource('yaml', __DIR__.'/../resources/locales/fr.yml', 'fr');

    return $translator;
}));
// Temporarly hack
$app['translator.domains'] = array();

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../log/app.log',
    'monolog.name'    => 'app',
    'monolog.level'   => 300 // = Logger::WARNING
));

$app->register(new TwigServiceProvider(), array(
    'twig.options'        => array('cache' => false, 'strict_variables' => true),
    'twig.form.templates' => array('form_div_layout.html.twig', 'common/form_div_layout.html.twig'),
    'twig.path'           => array(__DIR__ . '/../views')
));

$app->register(new AsseticExtension(), array(
    'assetic.options' => array(
        'debug' => $app['debug'],
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
