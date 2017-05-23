<?php

namespace AmaTeam\Vagranted\DI\Pass\Installation;

use AmaTeam\Vagranted\DI\Pass\AbstractMethodInjectionPass;
use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\DI\Tags;

/**
 * @author Etki <etki@etki.me>
 */
class LoaderPass extends AbstractMethodInjectionPass
{
    protected function getServiceId()
    {
        return References::INSTALLATION_LOADER;
    }

    protected function getInjectedTag()
    {
        return Tags::ASPECT_LOADER;
    }

    protected function getInjectionMethod()
    {
        return 'addAspectLoader';
    }
}
