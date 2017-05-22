<?php

namespace AmaTeam\Vagranted\Model\ResourceSet;

use AmaTeam\Vagranted\Model\Filesystem\WorkspaceInterface;

/**
 * @author Etki <etki@etki.me>
 */
class ResourceSet implements ResourceSetInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Configuration $configuration;
     */
    private $configuration;

    /**
     * @var string[][]
     */
    private $templates = [];

    /**
     * @var string[][]
     */
    private $assets = [];

    /**
     * @var WorkspaceInterface
     */
    private $workspace;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param Configuration $configuration
     * @return $this
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * @return string[][]
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @param string[][] $templates
     * @return $this
     */
    public function setTemplates($templates)
    {
        $this->templates = $templates;
        return $this;
    }

    /**
     * @return string[][]
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * @param string[][] $assets
     * @return $this
     */
    public function setAssets($assets)
    {
        $this->assets = $assets;
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
    public function setWorkspace($workspace)
    {
        $this->workspace = $workspace;
        return $this;
    }
}
