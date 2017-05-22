<?php

namespace AmaTeam\Vagranted\Filesystem;

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

    public function getInstallationDirectory()
    {
        return $this->join(
            $this->configuration->get()->getDataDirectory(),
            'resource-sets'
        );
    }

    public function getCacheDirectory()
    {
        return $this->join($this->configuration->get()->getDataDirectory(), 'cache');
    }

    public function getWorkingDirectory()
    {
        return $this->configuration->get()->getWorkingDirectory();
    }

    public function getInstallLocation($id)
    {
        return $this->join($this->getInstallationDirectory(), $id);
    }

    public function getConfigurationDirectory()
    {
        return $this->join(
            $this->configuration->get()->getProjectDirectory(),
            'resources',
            'configuration'
        );
    }

    public function getSourceDirectory()
    {
        return Helper::getInstallationRoot();
    }

    public function getProjectDirectory()
    {
        return $this->configuration->get()->getProjectDirectory();
    }

    private function join()
    {
        return implode(DIRECTORY_SEPARATOR, func_get_args());
    }
}
