<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bridge\PsrHttpMessage\Bundle\DependencyInjection;

use Laminas\Diactoros\RequestFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Alexander M. Turek <me@derrabus.de>
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('psr_http_message');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode->children()
            ->arrayNode('message_factories')
                ->{class_exists(Psr17Factory::class) || class_exists(ServerRequestFactory::class) ? 'canBeDisabled' : 'canBeEnabled'}()
                ->children()
                    ->enumNode('implementation')
                        ->info('The PSR-7 implementation to use. By default, the bundle will configure the Nyholm implementation if it\'s available.')
                        ->values(['nyholm', 'diactoros'])
                        ->defaultValue(class_exists(Psr17Factory::class) || !class_exists(ServerRequestFactory::class) ? 'nyholm' : 'diactoros')
                        ->validate()
                            ->ifTrue(function ($value) {
                                return 'nyholm' === $value && !class_exists(Psr17Factory::class);
                            })->thenInvalid('Cannot configure Nyholm\'s PSR-7 implementation. Try running "composer require nyholm/psr7".')
                        ->end()
                        ->validate()
                            ->ifTrue(function ($value) {
                                return 'diactoros' === $value && !class_exists(RequestFactory::class);
                            })->thenInvalid('Cannot configure Diactoros. Try running "composer require laminas/laminas-diactoros".')
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('message_converters')
                ->info('Converters that enable controllers to operate on PSR-7 messages')
                ->canBeEnabled()
            ->end()
            ->integerNode('response_buffer')
                ->info('The maximum output buffering size for each iteration when sending the response')
                ->min(1)
                ->defaultValue(16372)
            ->end()
        ;

        return $treeBuilder;
    }
}
