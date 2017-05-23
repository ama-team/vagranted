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
    protected function getServiceId()
    {
        return References::INSTALLER_COLLECTION;
    }

    protected function getInjectedTag()
    {
        return Tags::INSTALLER;
    }

    protected function getInjectionMethod()
    {
        return 'add';
    }
}
