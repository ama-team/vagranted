<?php

namespace AmaTeam\Vagranted\Model\Compilation;

use AmaTeam\Vagranted\Model\Project;
use AmaTeam\Vagranted\Model\ResourceSet\ResourceSetInterface;

/**
 * @author Etki <etki@etki.me>
 */
class Context
{
    /**
     * List of resource sets taking part in compilation in format of
     * [name => $set]. Current project is accessible under `_project` name.
     *
     * @var ResourceSetInterface[]
     */
    private $resourceSets;

    /**
     * Free-form data structure used as context in twig templates.
     *
     * @var array
     */
    private $context;

    /**
     * Project that is being compiled.
     *
     * @var Project
     */
    private $project;

    /**
     * @return ResourceSetInterface[]
     */
    public function getResourceSets()
    {
        return $this->resourceSets;
    }

    /**
     * @param ResourceSetInterface[] $resourceSets
     * @return $this
     */
    public function setResourceSets($resourceSets)
    {
        $this->resourceSets = $resourceSets;
        return $this;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param array $context
     * @return $this
     */
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     * @return $this
     */
    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }
}
