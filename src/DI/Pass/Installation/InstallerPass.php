<?php

namespace AmaTeam\Vagranted\DI\Pass\Installation;

use AmaTeam\Vagranted\DI\Pass\AbstractMethodInjectionPass;
use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\DI\Tags;

/**
 * @author Etki <etki@etki.me>
 */
class InstallerPass extends AbstractMethodInjectionPass
{
    function getServiceId()
    {
        return References::INSTALLER_COLLECTION;
    }

    function getInjectedTag()
    {
        return Tags::INSTALLER;
    }

    function getInjectionMethod()
    {
        return 'add';
    }
}
