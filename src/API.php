<?php

namespace AmaTeam\Vagranted;

use AmaTeam\Vagranted\API\CompilationAPI;
use AmaTeam\Vagranted\API\InstallerAPI;
use AmaTeam\Vagranted\API\ResourceSetAPI;
use AmaTeam\Vagranted\Console\Application;
use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\Application\VersionProvider;
use AmaTeam\Vagranted\Model\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Vagranted domain model.
 *
 * @author Etki <etki@etki.me>
 */
class API
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return VersionProvider
     */
    public function getVersionProvider()
    {
        /** @var VersionProvider $provider */
        $provider = $this->container->get(References::VERSION_PROVIDER);
        return $provider;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = $this->container->get(References::EVENT_DISPATCHER);
        return $dispatcher;
    }

    /**
     * @return Application
     */
    public function getConsoleApplication()
    {
        /** @var Application $application */
        $application = $this->container->get(References::CONSOLE_APPLICATION);
        return $application;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return CompilationAPI
     */
    public function getCompilationAPI()
    {
        /** @var CompilationAPI $api */
        $api = $this->container->get(References::COMPILATION_API);
        return $api;
    }

    /**
     * @return ResourceSetAPI
     */
    public function getResourceSetAPI()
    {
        /** @var ResourceSetAPI $api */
        $api = $this->container->get(References::RESOURCE_SET_API);
        return $api;
    }

    /**
     * @return InstallerAPI
     */
    public function getInstallerAPI()
    {
        /** @var InstallerAPI $api */
        $api = $this->container->get(References::INSTALLER_API);
        return $api;
    }

    /**
     * @return ConfigurationInterface
     */
    public function getConfiguration()
    {
        /** @var ConfigurationInterface $container */
        $container = $this->container->get(References::CONFIGURATION);
        return $container;
    }
}
