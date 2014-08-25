<?php

namespace Dominikzogg\EnergyCalculator;

use Dominikzogg\EnergyCalculator\Command\UserCreateCommand;
use Dominikzogg\EnergyCalculator\Entity\User;
use Dominikzogg\EnergyCalculator\Form\SimpleDateTypeExtension;
use Dominikzogg\EnergyCalculator\Provider\MenuProvider;
use Dominikzogg\EnergyCalculator\Twig\FormHelperExtension;
use Saxulum\BundleProvider\Provider\AbstractBundleProvider;
use Saxulum\UserProvider\Silex\Provider\SaxulumUserProvider;
use Silex\Application;

class EnergyCalculatorProvider extends AbstractBundleProvider
{
    public function register(Application $app)
    {
        $app->register(new MenuProvider());
        
        $app->register(new SaxulumUserProvider(), array(
            'saxulum.userprovider.userclass' => get_class(new User())
        ));
        
        $app['twig'] = $app->share($app->extend('twig', function(\Twig_Environment $twig) {
            $twig->addExtension(new FormHelperExtension());

            return $twig;
        }));

        $app['form.extensions'] = $app->share($app->extend('form.extensions', function ($extensions) use ($app) {
            $extensions[] = new SimpleDateTypeExtension();

            return $extensions;
        }));

        $app['security.access_rules'] = $app->share($app->extend('security.access_rules', function ($rules) use ($app) {
            $rules[] = array('^/_profiler*', 'IS_AUTHENTICATED_ANONYMOUSLY');
            $rules[] = array('^/[^/]*/admin', 'ROLE_ADMIN');
            $rules[] = array('^/[^/]*', 'ROLE_USER');

            return $rules;
        }));

        $app['console.commands'] = $app->share(
            $app->extend('console.commands', function (array $commands) use ($app) {
                $commands[] = new UserCreateCommand(null, $app['saxulum.userprovider.userclass']);

                return $commands;
            })
        );

        $this->addControllers($app);
        $this->addDoctrineOrmMappings($app);
        $this->addTranslatorRessources($app);
        $this->addTwigLoaderFilesystemPath($app);
    }

    public function boot(Application $app) {}
}
