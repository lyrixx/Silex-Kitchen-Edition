<?php

namespace App;

use Silex\Application as SilexApplication;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Translation\Loader\YamlFileLoader;

class Application extends SilexApplication
{
    private $baseDir;

    public function __construct($configFile = null)
    {
        $this->baseDir = __DIR__.'/../../';

        parent::__construct();

        $app = $this;

        if ($configFile) {
            if (file_exists($configFile)) {
                require $configFile;
            } else {
                throw new \RuntimeException(sprintf('The file "%s" does not exist.', $configFile));
            }
        }

        $app->register(new HttpCacheServiceProvider());
        $app->register(new SessionServiceProvider());
        $app->register(new ValidatorServiceProvider());
        $app->register(new FormServiceProvider());
        $app->register(new UrlGeneratorServiceProvider());
        $app->register(new DoctrineServiceProvider());

        $app->register(new SecurityServiceProvider(), array(
            'security.firewalls' => array(
                'admin' => array(
                    'pattern' => '^/',
                    'form' => array(
                        'login_path' => '/login',
                    ),
                    'logout' => true,
                    'anonymous' => true,
                    'users' => $app['security.users'],
                ),
            ),
        ));
        $app['security.encoder.digest'] = $app->share(function ($app) {
            return new PlaintextPasswordEncoder();
        });
        $app['security.utils'] = $app->share(function ($app) {
            return new AuthenticationUtils($app['request_stack']);
        });

        $app->register(new TranslationServiceProvider());
        $app['translator'] = $app->share($app->extend('translator', function ($translator, $app) {
            $translator->addLoader('yaml', new YamlFileLoader());
            $translator->addResource('yaml', $this->baseDir.'/resources/locales/fr.yml', 'fr');

            return $translator;
        }));

        $app->register(new MonologServiceProvider(), array(
            'monolog.logfile' => $this->baseDir.'/resources/log/app.log',
            'monolog.name' => 'app',
            'monolog.level' => 300, // = Logger::WARNING
        ));

        $app->register(new TwigServiceProvider(), array(
            'twig.options' => array(
                'cache' => isset($app['twig.options.cache']) ? $app['twig.options.cache'] : false,
                'strict_variables' => true,
            ),
            'twig.form.templates' => array('bootstrap_3_horizontal_layout.html.twig'),
            'twig.path' => array($this->baseDir.'/resources/views'),
        ));
        $app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
            $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
                $base = $app['request_stack']->getCurrentRequest()->getBasePath();

                return sprintf($base.'/'.$asset, ltrim($asset, '/'));
            }));

            return $twig;
        }));

        $app->mount('', new ControllerProvider());
    }
}
