<?php

namespace AmaTeam\Vagranted\Model\ResourceSet;

use AmaTeam\Vagranted\Model\ResourceSet\Configuration\AssetFilter;
use AmaTeam\Vagranted\Model\ResourceSet\Configuration\TemplateFilter;

/**
 * @author Etki <etki@etki.me>
 */
class Configuration
{
    /**
     * Resource set id (optional, used only to identify root resource set).
     *
     * @var string
     */
    private $id;

    /**
     * Resource set name (optional)
     *
     * @var string
     */
    private $name;

    /**
     * Resource set description (optional)
     *
     * @var string
     */
    private $description;

    /**
     * List of parent resource sets in format of [$name => $url]
     *
     * @var string[]
     */
    private $dependencies = [];

    /**
     * Free-form context for rendering.
     *
     * @var array
     */
    private $context = [];

    /**
     * @var TemplateFilter[]
     */
    private $templates = [];

    /**
     * @var AssetFilter[]
     */
    private $assets = [];

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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * @param string[] $dependencies
     * @return $this
     */
    public function setDependencies($dependencies)
    {
        $this->dependencies = $dependencies;
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
     * @return TemplateFilter[]
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @param TemplateFilter[] $templates
     * @return $this
     */
    public function setTemplates($templates)
    {
        $this->templates = $templates;
        return $this;
    }

    /**
     * @return AssetFilter[]
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * @param AssetFilter[] $assets
     * @return $this
     */
    public function setAssets($assets)
    {
        $this->assets = $assets;
        return $this;
    }
}
