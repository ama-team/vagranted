<?php

namespace AmaTeam\Vagranted\DI\Pass\Event;

use AmaTeam\Vagranted\DI\Pass\AbstractMethodInjectionPass;
use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\DI\Tags;

/**
 * @author Etki <etki@etki.me>
 */
class ListenerPass extends AbstractMethodInjectionPass
{
    protected function getServiceId()
    {
        return References::EVENT_DISPATCHER;
    }

    protected function getInjectedTag()
    {
        return Tags::EVENT_SUBSCRIBER;
    }

    protected function getInjectionMethod()
    {
        return 'addSubscriber';
    }
}
