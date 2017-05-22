<?php

namespace AmaTeam\Vagranted\ResourceSet;

use AmaTeam\Vagranted\Application\Configuration\Constants;
use AmaTeam\Vagranted\Application\Configuration\Container;
use AmaTeam\Vagranted\Filesystem\Helper;
use AmaTeam\Vagranted\Filesystem\PatternLocator;
use AmaTeam\Vagranted\Model\Exception\RuntimeException;
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
     * @var Container
     */
    private $configuration;

    /**
     * @var ConfigurationReader
     */
    private $reader;

    /**
     * @var PatternLocator
     */
    private $locator;

    /**
     * @param Container $configuration
     * @param ConfigurationReader $reader
     * @param PatternLocator $locator
     */
    public function __construct(
        Container $configuration,
        ConfigurationReader $reader,
        PatternLocator $locator
    ) {
        $this->configuration = $configuration;
        $this->reader = $reader;
        $this->locator = $locator;
    }

    /**
     * @param string $path
     * @return ResourceSetInterface
     */
    public function read($path)
    {
        if (!Helper::isAbsolutePath($path)) {
            $base = $this->configuration->get()->getProjectDirectory();
            $path = $base . DIRECTORY_SEPARATOR . $path;
            $path = realpath($path);
        }
        if (!$path) {
            $message = 'Nonexisting resource set specified: ' . $path;
            throw new RuntimeException($message);
        }
        $configuration = $this->readConfiguration($path);
        return (new ResourceSet())
            ->setName($path)
            ->setWorkspace(new Workspace($path))
            ->setConfiguration($this->readConfiguration($path))
            ->setAssets($this->locateAssets($path, $configuration))
            ->setTemplates($this->locateTemplates($path, $configuration));
    }

    private function readConfiguration($path)
    {
        $chunks = [$path, Constants::RESOURCE_SET_CONFIGURATION_FILE];
        $location = implode(DIRECTORY_SEPARATOR, $chunks);
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
