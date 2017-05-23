<?php

namespace AmaTeam\Vagranted\DI\Pass\Compilation;

use AmaTeam\Vagranted\DI\Pass\AbstractMethodInjectionPass;
use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\DI\Tags;

/**
 * @author Etki <etki@etki.me>
 */
class AspectCompilerPass extends AbstractMethodInjectionPass
{
    protected function getServiceId()
    {
        return References::ASPECT_COMPILER_COLLECTION;
    }

    protected function getInjectedTag()
    {
        return Tags::ASPECT_COMPILER;
    }

    protected function getInjectionMethod()
    {
        return 'add';
    }
}
