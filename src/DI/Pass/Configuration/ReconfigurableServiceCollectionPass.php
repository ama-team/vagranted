<?php

namespace AmaTeam\Vagranted\DI\Pass\Configuration;

use AmaTeam\Vagranted\DI\Pass\AbstractMethodInjectionPass;
use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\DI\Tags;

/**
 * Collects all reconfigurable services and pushes them into corresponding
 * manager.
 *
 * @author Etki <etki@etki.me>
 */
class ReconfigurableServiceCollectionPass extends AbstractMethodInjectionPass
{
    function getServiceId()
    {
        return References::CONFIGURATION_DISTRIBUTOR;
    }

    function getInjectedTag()
    {
        return Tags::RECONFIGURABLE;
    }

    function getInjectionMethod()
    {
        return 'add';
    }
}
