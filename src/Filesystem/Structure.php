<?php

namespace AmaTeam\Vagranted\Filesystem;

use AmaTeam\Pathetic\Path;
use AmaTeam\Vagranted\Application\Configuration\Constants;
use AmaTeam\Vagranted\Model\ConfigurationInterface;

/**
 * Provides other classes with various locations.
 *
 * @author Etki <etki@etki.me>
 */
class Structure
{
    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    /**
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getDataDirectory()
    {
        return Path::parse($this->configuration->getDataDirectory());
    }

    public function getInstallationDirectory()
    {
        return $this
            ->getDataDirectory()
            ->resolve(Constants::RESOURCE_SET_DIRECTORY);
    }

    public function getCacheDirectory()
    {
        return $this->getDataDirectory()->resolve(Constants::CACHE_DIRECTORY);
    }

    public function getWorkingDirectory()
    {
        return Path::parse($this->configuration->getWorkingDirectory());
    }

    public function getConfigurationDirectory()
    {
        return $this
            ->getProjectDirectory()
            ->resolve(Constants::CONFIGURATION_DIRECTORY);
    }

    public function getSourceDirectory()
    {
        return Path::parse(Helper::getInstallationRoot());
    }

    public function getProjectDirectory()
    {
        return Path::parse($this->configuration->getProjectDirectory());
    }
}
