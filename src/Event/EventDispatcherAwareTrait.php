<?php

namespace AmaTeam\Vagranted\Event;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Etki <etki@etki.me>
 */
trait EventDispatcherAwareTrait
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @return $this
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
        return $this;
    }
}
