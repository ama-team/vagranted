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
    protected function getServiceId()
    {
        return References::CONSOLE_APPLICATION;
    }

    protected function getInjectedTag()
    {
        return Tags::CONSOLE_COMMAND;
    }

    protected function getInjectionMethod()
    {
        return 'add';
    }
}
