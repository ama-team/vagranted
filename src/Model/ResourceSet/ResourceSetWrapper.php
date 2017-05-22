<?php

namespace AmaTeam\Vagranted\Model\ResourceSet;

/**
 * @author Etki <etki@etki.me>
 */
class ResourceSetWrapper implements ResourceSetInterface
{
    /**
     * @var ResourceSetInterface
     */
    private $set;

    /**
     * @param ResourceSetInterface $set
     */
    public function __construct(ResourceSetInterface $set)
    {
        $this->set = $set;
    }

    public function getConfiguration()
    {
        return $this->set->getConfiguration();
    }

    public function getWorkspace()
    {
        return $this->set->getWorkspace();
    }

    public function getAssets()
    {
        return $this->set->getAssets();
    }

    public function getTemplates()
    {
        return $this->set->getTemplates();
    }

    public function getName()
    {
        return $this->set->getName();
    }
}
