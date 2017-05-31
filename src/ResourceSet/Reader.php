<?php

namespace AmaTeam\Vagranted\ResourceSet;

use AmaTeam\Pathetic\Path;
use AmaTeam\Vagranted\Application\Configuration\Constants;
use AmaTeam\Vagranted\Filesystem\FilePatternLocator;
use AmaTeam\Vagranted\Filesystem\Structure;
use AmaTeam\Vagranted\Model\Filesystem\Workspace;
use AmaTeam\Vagranted\Model\ResourceSet\Configuration;
use AmaTeam\Vagranted\Model\ResourceSet\ResourceSet;
use AmaTeam\Vagranted\Model\ResourceSet\ResourceSetInterface;
use AmaTeam\Vagranted\ResourceSet\Configuration\Reader as ConfigurationReader;

/**
 * @author Etki <etki@etki.me>
 */
class Reader
{
    /**
     * @var Structure
     */
    private $structure;

    /**
     * @var ConfigurationReader
     */
    private $reader;

    /**
     * @var FilePatternLocator
     */
    private $locator;

    /**
     * @param Structure $structure
     * @param ConfigurationReader $reader
     * @param FilePatternLocator $locator
     */
    public function __construct(
        Structure $structure,
        ConfigurationReader $reader,
        FilePatternLocator $locator
    ) {
        $this->structure = $structure;
        $this->reader = $reader;
        $this->locator = $locator;
    }

    /**
     * @param Path $path
     * @return ResourceSetInterface
     */
    public function read(Path $path)
    {
        $path = $this->structure->getProjectDirectory()->resolve($path);
        $configuration = $this->readConfiguration($path);
        return (new ResourceSet())
            ->setName($path)
            ->setWorkspace(new Workspace($path))
            ->setConfiguration($configuration)
            ->setAssets($this->locateAssets($path, $configuration))
            ->setTemplates($this->locateTemplates($path, $configuration));
    }

    private function readConfiguration(Path $path)
    {
        $location = $path->resolve(Constants::RESOURCE_SET_CONFIGURATION_FILE);
        return $this->reader->read($location);
    }

    private function locateAssets($path, Configuration $configuration)
    {
        return $this->locator->locateMany($path, $configuration->getAssets());
    }

    private function locateTemplates($path, Configuration $configuration)
    {
        return $this->locator->locateMany(
            $path,
            $configuration->getTemplates()
        );
    }
}
