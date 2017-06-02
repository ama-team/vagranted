<?php

namespace AmaTeam\Vagranted\Project;

use AmaTeam\Vagranted\Model\ConfigurationInterface;
use AmaTeam\Vagranted\Model\Filesystem\Workspace;
use AmaTeam\Vagranted\Model\Project;
use AmaTeam\Vagranted\ResourceSet\Reader;

/**
 * @author Etki <etki@etki.me>
 */
class Loader
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    /**
     * @param Reader $reader
     * @param ConfigurationInterface $configuration
     */
    public function __construct(
        Reader $reader,
        ConfigurationInterface $configuration
    ) {
        $this->reader = $reader;
        $this->configuration = $configuration;
    }

    /**
     * Returns project definition
     *
     * @return Project
     */
    public function load()
    {
        $path = $this->configuration->getProjectDirectory();
        return (new Project())
            ->setWorkspace(new Workspace($path))
            ->setSet($this->reader->read($path));
    }
}
