<?php

namespace AmaTeam\Vagranted\Event;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Etki <etki@etki.me>
 */
interface EventDispatcherAwareInterface
{
    public function setEventDispatcher(EventDispatcherInterface $dispatcher);
}
