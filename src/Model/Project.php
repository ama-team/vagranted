<?php

namespace AmaTeam\Vagranted\Model;

use AmaTeam\Vagranted\Model\Filesystem\WorkspaceInterface;
use AmaTeam\Vagranted\Model\ResourceSet\ResourceSetInterface;

/**
 * This class describes project itself. Serves mostly as stub that may be
 * expanded later.
 *
 * @author Etki <etki@etki.me>
 */
class Project
{
    /**
     * @var WorkspaceInterface
     */
    private $workspace;
    /**
     * @var ResourceSetInterface
     */
    private $set;

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
    public function setWorkspace($workspace)
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
}
