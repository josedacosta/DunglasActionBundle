<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\ActionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Console\Command\Command;

/**
 * {@inheritdoc}
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('dunglas_action')
            ->fixXmlConfig('directory', 'directories')
            ->children()
                ->arrayNode('autodiscover')
                    ->info('Autodiscover action classes stored in the configured directory of bundles and register them as service.')
                    ->canBeDisabled()
                    ->fixXmlConfig('directory', 'directories')
                    ->children()
                        ->arrayNode('directories')
                            ->info('The directory name to autodiscover in bundles.')
                            ->useAttributeAsKey('prefix')
                            ->prototype('array')
                                ->prototype('scalar')->end()
                            ->end()
                            ->defaultValue(['controller' => ['Action']])
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('directories')
                    ->info('List of directories relative to the kernel root directory containing classes.')
                    ->useAttributeAsKey('prefix')
                    ->prototype('array')
                        ->prototype('scalar')->end()
                    ->end()
                    ->defaultValue(call_user_func(function () {
                        $defaultValue = ['controller' => ['../src/*Bundle/Controller']];
                        if (class_exists(Command::class)) {
                            $defaultValue['command'] = ['../src/*Bundle/Command'];
                        }

                        return $defaultValue;
                    }))
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
