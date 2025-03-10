<?php

namespace VeeZions\BuilderEngine\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use VeeZions\BuilderEngine\Constant\ConfigConstant;

class BuilderEngineExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../config')
        );

        $loader->load('repositories.php');
        $loader->load('services.php');

        if (ConfigConstant::CONFIG_MODE_FORM !== $config['mode']) {
            $loader->load('controllers.php');
        }

        if (true === $container->getParameter('kernel.debug')) {
            $loader->load('debug.php');
        }

        $loader->load('twig.php');
        $loader->load('command.php');

        $this->setControllersArgumentsFromConfig($config, $container);
    }

    public function getConfiguration(array $config, ContainerBuilder $container): BuilderEngineConfiguration
    {
        return new BuilderEngineConfiguration();
    }

    private function setControllersArgumentsFromConfig(array $config, ContainerBuilder $container): void
    {
        $liipFilters = $container->hasParameter('liip_imagine.filter_sets') 
            ? $container->getParameter('liip_imagine.filter_sets') 
            : [];
        
        $routeLoaderDefinition = $container->getDefinition('veezions_builder_engine.route_loader');
        $routeLoaderDefinition->setArgument('$mode', $config['mode']);
        $routeLoaderDefinition->setArgument('$prefix', $config['crud_prefix']);
        $routeLoaderDefinition->setArgument('$actionsConfig', $config['actions']);

        $importCommandDefinition = $container->getDefinition('veezions_builder_engine.command.import_templates');
        $importCommandDefinition->setArgument('$mode', $config['mode']);

        if (ConfigConstant::CONFIG_MODE_FORM !== $config['mode']) {
            $articlesDefinition = $container->getDefinition('veezions_builder_engine.article_controller');
            $articlesDefinition->setArgument('$actions', $config['actions']['articles']);

            $pagesDefinition = $container->getDefinition('veezions_builder_engine.page_controller');
            $pagesDefinition->setArgument('$actions', $config['actions']['pages']);

            $categoriesDefinition = $container->getDefinition('veezions_builder_engine.category_controller');
            $categoriesDefinition->setArgument('$actions', $config['actions']['categories']);

            $navigationsDefinition = $container->getDefinition('veezions_builder_engine.navigation_controller');
            $navigationsDefinition->setArgument('$actions', $config['actions']['navigations']);
        }
        
        $formManagerDefinition = $container->getDefinition('veezions_builder_engine.form_manager');
        $formManagerDefinition->setArgument('$authors', $config['author_providers']);
        $formManagerDefinition->setArgument('$bundleMode', $config['mode']);
        $formManagerDefinition->setArgument('$libraryLiipFilters', $config['library_config']['liip_filter_sets']);
        
        $assetConstantDefinition = $container->getDefinition('veezions_builder_engine.asset_constant');
        $assetConstantDefinition->setArgument('$liipFilters', $liipFilters);
        
        $globalVariableDefinition = $container->getDefinition('veezions_builder_engine.twig.global');
        $globalVariableDefinition->setArgument('$extended_template', $config['extended_template']);
        $globalVariableDefinition->setArgument('$form_theme', $config['form_theme']);
    }
}
