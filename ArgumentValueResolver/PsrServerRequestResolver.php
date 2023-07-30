<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bridge\PsrHttpMessage\ArgumentValueResolver;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Injects the RequestInterface, MessageInterface or ServerRequestInterface when requested.
 *
 * @author Iltar van der Berg <kjarli@gmail.com>
 * @author Alexander M. Turek <me@derrabus.de>
 */
final class PsrServerRequestResolver implements ValueResolverInterface
{
    private const SUPPORTED_TYPES = [
        ServerRequestInterface::class => true,
        RequestInterface::class => true,
        MessageInterface::class => true,
    ];

    public function __construct(
        private readonly HttpMessageFactoryInterface $httpMessageFactory,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Traversable
    {
        if (!isset(self::SUPPORTED_TYPES[$argument->getType()])) {
            return;
        }

        yield $this->httpMessageFactory->createRequest($request);
    }
}
