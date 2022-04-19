<?php

namespace Symfony\Bridge\PsrHttpMessage\Tests\Fixtures;

use Symfony\Component\HttpFoundation\Response;

class FalseContentResponse extends Response
{
    /**
     * Sets the response content to null
     *
     * @return void
     */
    public function setContentFalse()
    {
        $this->content = false;
    }
}