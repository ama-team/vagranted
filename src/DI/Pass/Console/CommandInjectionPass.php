<?php

namespace AmaTeam\Vagranted\DI\Pass\Console;

use AmaTeam\Vagranted\DI\Pass\AbstractMethodInjectionPass;
use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\DI\Tags;

/**
 * @author Etki <etki@etki.me>
 */
class CommandInjectionPass extends AbstractMethodInjectionPass
{
    function getServiceId()
    {
        return References::CONSOLE_APPLICATION;
    }

    function getInjectedTag()
    {
        return Tags::CONSOLE_COMMAND;
    }

    function getInjectionMethod()
    {
        return 'add';
    }
}
