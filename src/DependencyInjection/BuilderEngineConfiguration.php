<?php

namespace VeeZions\BuilderEngine\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use VeeZions\BuilderEngine\Constant\ConfigConstant;

class BuilderEngineConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(ConfigConstant::CONFIG_FILE_NAME);

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('author_providers')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('articles')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('author_class')->defaultValue(null)->end()
                                ->scalarNode('author_roles')->defaultValue(null)->end()
                                ->scalarNode('author_placeholder')->defaultValue('id')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                        ->arrayNode('pages')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('author_class')->defaultValue(null)->end()
                                ->scalarNode('author_roles')->defaultValue(null)->end()
                                ->scalarNode('author_placeholder')->defaultValue('id')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('library_config')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('driver')
                            ->defaultValue(ConfigConstant::CONFIG_DEFAULT_DRIVER)
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('service')->defaultValue(null)->end()
                        ->arrayNode('liip_filter_sets')
                            ->scalarPrototype()->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('mode')
                    ->defaultValue(ConfigConstant::CONFIG_MODE_DEFAULT)
                    ->info('Possible values: '.ConfigConstant::CONFIG_MODE_DEFAULT.', '.ConfigConstant::CONFIG_MODE_FORM)
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('crud_prefix')
                    ->defaultValue(ConfigConstant::CONFIG_DEFAULT_CRUD_PREFIX)
                    ->info('Add a prefix to controllers URLs in "'.ConfigConstant::CONFIG_MODE_DEFAULT.'" mode')
                ->end()
                ->scalarNode('extended_template')
                    ->defaultValue(ConfigConstant::CONFIG_EXTENDED_TEMPLATE)
                    ->cannotBeEmpty()
                    ->info('Use your own extended template in "'.ConfigConstant::CONFIG_MODE_DEFAULT.'" mode')
                ->end()
                ->scalarNode('form_theme')
                    ->defaultValue(null)
                    ->info('form_theme to use in builder forms in "'.ConfigConstant::CONFIG_MODE_DEFAULT.'" mode')
                ->end()
                ->arrayNode('actions')
                    ->addDefaultsIfNotSet()
                    ->info('Set granted roles to allow access to each actions like ["ROLE_USER"] for each controller in "'.ConfigConstant::CONFIG_MODE_DEFAULT.'" mode.')
                    ->children()
                        ->arrayNode('categories')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('new')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('show')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('edit')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('delete')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('articles')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('new')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('show')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('edit')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('delete')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('pages')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('new')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('show')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('edit')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('delete')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('navigations')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('new')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('show')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('edit')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('delete')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('libraries')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('delete')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('enabled')->defaultValue(true)->treatNullLike(true)->end()
                                        ->arrayNode('roles')->scalarPrototype()->end()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
