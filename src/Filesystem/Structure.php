<?php

namespace AmaTeam\Vagranted\Filesystem;

use AmaTeam\Pathetic\Path;
use AmaTeam\Vagranted\Application\Configuration\Container;

/**
 * Provides other classes with various locations.
 *
 * @author Etki <etki@etki.me>
 */
class Structure
{
    /**
     * @var Container
     */
    private $configuration;

    /**
     * @param Container $configuration
     */
    public function __construct(Container $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getDataDirectory()
    {
        return Path::parse($this->configuration->get()->getDataDirectory());
    }

    public function getInstallationDirectory()
    {
        // todo hardcode
        return $this->getDataDirectory()->resolve('resource-sets');
    }

    public function getCacheDirectory()
    {
        // todo hardcode
        return $this->getDataDirectory()->resolve('cache');
    }

    public function getWorkingDirectory()
    {
        return Path::parse($this->configuration->get()->getWorkingDirectory());
    }

    public function getConfigurationDirectory()
    {
        // todo hardcode
        return $this->getProjectDirectory()->resolve('resources/configuration');
    }

    public function getSourceDirectory()
    {
        return Path::parse(Helper::getInstallationRoot());
    }

    public function getProjectDirectory()
    {
        return Path::parse($this->configuration->get()->getProjectDirectory());
    }
}
