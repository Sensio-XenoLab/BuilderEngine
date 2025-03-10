<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use VeeZions\BuilderEngine\Twig\Runtime\FiltersRuntime;
use VeeZions\BuilderEngine\Twig\Extension\FiltersExtension;
use VeeZions\BuilderEngine\Twig\GlobalVariables;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services
        ->set('veezions_builder_engine.twig.extension', FiltersExtension::class)
        ->tag('twig.extension')
    ;

    $services
        ->set('veezions_builder_engine.twig.global', GlobalVariables::class)
        ->args([
            abstract_arg('Get config.extended_template value'),
            abstract_arg('Get config.form_theme value'),
        ])
        ->tag('twig.global')
    ;

    $services
        ->set('veezions_builder_engine.twig.page_runtime', FiltersRuntime::class)
        ->args([
            service('veezions_builder_engine.navigation_manager'),
        ])
        ->tag('twig.runtime')
    ;
};
