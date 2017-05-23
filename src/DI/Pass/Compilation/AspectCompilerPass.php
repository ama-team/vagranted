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
    function getServiceId()
    {
        return References::ASPECT_COMPILER_COLLECTION;
    }

    function getInjectedTag()
    {
        return Tags::ASPECT_COMPILER;
    }

    function getInjectionMethod()
    {
        return 'add';
    }
}
