<?php

namespace AmaTeam\Vagranted\Event\Event\ResourceSet;

use AmaTeam\Vagranted\Model\ResourceSet\ResourceSetInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * @author Etki <etki@etki.me>
 */
class AbstractResourceSetEvent extends Event
{
    /**
     * @var ResourceSetInterface
     */
    private $resourceSet;

    /**
     * @param ResourceSetInterface $resourceSet
     */
    public function __construct(ResourceSetInterface $resourceSet)
    {
        $this->resourceSet = $resourceSet;
    }

    /**
     * @return ResourceSetInterface
     */
    public function getResourceSet()
    {
        return $this->resourceSet;
    }

    /**
     * @param ResourceSetInterface $resourceSet
     * @return $this
     */
    public function setResourceSet(ResourceSetInterface $resourceSet)
    {
        $this->resourceSet = $resourceSet;
        return $this;
    }
}
