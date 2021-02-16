<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bridge\PsrHttpMessage\Form;

use Symfony\Component\Form\AbstractExtension;

/**
 * Class Psr7Extension.
 */
class Psr7Extension extends AbstractExtension
{
    /**
     * @return FormTypePsr7Extension[]
     */
    protected function loadTypeExtensions(): array
    {
        return [
            new FormTypePsr7Extension(),
        ];
    }
}
