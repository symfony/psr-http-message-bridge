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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * @author Alexander M. Turek <me@derrabus.de>
 */
final class PsrHttpMessageExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(\dirname(__DIR__).'/Resources/config'));
        $loader->load('http_foundation.php');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('psr_http_message.response_buffer', $config['response_buffer']);

        if ($config['message_converters']['enabled']) {
            $loader->load('message_converters.php');
        }

        switch ($config['message_factories']['enabled'] ? $config['message_factories']['implementation'] : 'disabled') {
            case 'nyholm':
                $loader->load('nyholm.php');
                $loader->load('psr_factories.php');
                break;
            case 'diactoros':
                $loader->load('diactoros.php');
                $loader->load('psr_factories.php');
                break;
            default:
                $container->removeDefinition('psr_http_message.server_request_resolver');
                break;
        }
    }
}
