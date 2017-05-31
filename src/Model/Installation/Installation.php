<?php

namespace AmaTeam\Vagranted\Model\Installation;

use AmaTeam\Vagranted\Model\Filesystem\WorkspaceInterface;
use AmaTeam\Vagranted\Model\ResourceSet\ResourceSetInterface;

/**
 * @author Etki <etki@etki.me>
 */
class Installation
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var WorkspaceInterface
     */
    private $workspace;

    /**
     * @var ResourceSetInterface
     */
    private $set;

    /**
     * @var StatisticsInterface
     */
    private $statistics;

    /**
     * @var Specification
     */
    private $specification;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return WorkspaceInterface
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }

    /**
     * @param WorkspaceInterface $workspace
     * @return $this
     */
    public function setWorkspace(WorkspaceInterface $workspace)
    {
        $this->workspace = $workspace;
        return $this;
    }

    /**
     * @return ResourceSetInterface
     */
    public function getSet()
    {
        return $this->set;
    }

    /**
     * @param ResourceSetInterface $set
     * @return $this
     */
    public function setSet($set)
    {
        $this->set = $set;
        return $this;
    }

    /**
     * @return StatisticsInterface
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param StatisticsInterface $statistics
     * @return $this
     */
    public function setStatistics($statistics)
    {
        $this->statistics = $statistics;
        return $this;
    }

    /**
     * @return Specification
     */
    public function getSpecification()
    {
        return $this->specification;
    }

    /**
     * @param Specification $specification
     * @return $this
     */
    public function setSpecification($specification)
    {
        $this->specification = $specification;
        return $this;
    }
}
