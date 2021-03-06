<?php

use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Igorw\Silex\ConfigServiceProvider;
use Knp\Menu\Integration\Silex\KnpMenuServiceProvider;
use Silex\Application;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Saxulum\Accessor\AccessorRegistry;
use Saxulum\Accessor\Accessors\Add;
use Saxulum\Accessor\Accessors\Get;
use Saxulum\Accessor\Accessors\Is;
use Saxulum\Accessor\Accessors\Remove;
use Saxulum\Accessor\Accessors\Set;
use Saxulum\AsseticTwig\Silex\Provider\AsseticTwigProvider;
use Saxulum\Console\Silex\Provider\ConsoleProvider;
use Saxulum\DoctrineOrmManagerRegistry\Silex\Provider\DoctrineOrmManagerRegistryProvider;
use Saxulum\MenuProvider\Silex\Provider\SaxulumMenuProvider;
use Saxulum\PaginationProvider\Silex\Provider\SaxulumPaginationProvider;
use Saxulum\RouteController\Provider\RouteControllerProvider;
use Saxulum\SaxulumBootstrapProvider\Silex\Provider\SaxulumBootstrapProvider;
use Saxulum\SaxulumWebProfiler\Provider\SaxulumWebProfilerProvider;
use Saxulum\Translation\Silex\Provider\TranslationProvider;
use Saxulum\Validator\Silex\Provider\SaxulumValidatorProvider;

// annotation registry
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

function getRuntimeDir()
{
    $runtimeDirConfigFile = __DIR__ . '/runtime_dir_config.php';
    if(is_file($runtimeDirConfigFile)) {
        return require $runtimeDirConfigFile;
    }

    return __DIR__;
}

// accessor registry
AccessorRegistry::registerAccessor(new Add());
AccessorRegistry::registerAccessor(new Get());
AccessorRegistry::registerAccessor(new Is());
AccessorRegistry::registerAccessor(new Remove());
AccessorRegistry::registerAccessor(new Set());

// create new silex app
$app = new Application();

$app['root_dir'] = dirname(__DIR__);
$app['runtime_dir'] = getRuntimeDir();
$app['debug'] = isset($debug) ? $debug : false;
$app['env'] = isset($env) ? $env : 'prod';

$app->register(new TranslationServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new UrlGeneratorServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new HttpCacheServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new SecurityServiceProvider());
$app->register(new SwiftmailerServiceProvider());
$app->register(new MonologServiceProvider());
$app->register(new DoctrineServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new ConsoleProvider());
$app->register(new DoctrineOrmServiceProvider());
$app->register(new DoctrineOrmManagerRegistryProvider());
$app->register(new KnpMenuServiceProvider());
$app->register(new SaxulumMenuProvider());
$app->register(new AsseticTwigProvider());
$app->register(new RouteControllerProvider());
$app->register(new SaxulumPaginationProvider());
$app->register(new TranslationProvider());
$app->register(new SaxulumBootstrapProvider());
$app->register(new SaxulumValidatorProvider());

if ($app['debug']) {
    $app->register(new WebProfilerServiceProvider());
    $app->register(new SaxulumWebProfilerProvider());
}

// load all project providers
$app->register(new \Dominikzogg\EnergyCalculator\EnergyCalculatorProvider());

// config overrides
$app->register(new ConfigServiceProvider("{$app['root_dir']}/app/config/config.yml", array('root_dir' => $app['root_dir'], 'runtime_dir' => $app['runtime_dir'], 'env' => $app['env'])));
$app->register(new ConfigServiceProvider("{$app['root_dir']}/app/config/config_{$app['env']}.yml", array('root_dir' => $app['root_dir'], 'runtime_dir' => $app['runtime_dir'])));
$app->register(new ConfigServiceProvider("{$app['root_dir']}/app/config/parameters.yml"));

// return the app
return $app;
