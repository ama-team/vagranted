<?php

namespace AmaTeam\Vagranted\Application\Configuration;

use AmaTeam\Vagranted\Filesystem\Helper;
use AmaTeam\Vagranted\Filesystem\ProjectRootLocator;
use AmaTeam\Vagranted\Model\Configuration;
use AmaTeam\Vagranted\Model\Configuration\LoggerConfiguration;

/**
 * @author Etki <etki@etki.me>
 */
class Normalizer
{
    /**
     * @var ProjectRootLocator
     */
    private $projectRootLocator;

    /**
     * @param ProjectRootLocator $projectRootLocator
     */
    public function __construct(ProjectRootLocator $projectRootLocator)
    {
        $this->projectRootLocator = $projectRootLocator;
    }

    /**
     * Fills missing configuration fields with default values.
     *
     * @param Configuration $configuration
     * @return Configuration
     */
    public function normalize(Configuration $configuration)
    {
        $configuration = $configuration ?: new Configuration();
        if (!$configuration->getWorkingDirectory()) {
            $configuration->setWorkingDirectory(getcwd());
        }
        if (!Helper::isAbsolutePath($configuration->getWorkingDirectory())) {
            $workingDirectory = getcwd() .
                DIRECTORY_SEPARATOR .
                $configuration->getWorkingDirectory();
            $configuration->setWorkingDirectory($workingDirectory);
        }
        if (!$configuration->getProjectDirectory()) {
            $workingDirectory = $configuration->getWorkingDirectory();
            $projectDirectory = $this
                ->projectRootLocator
                ->locate($workingDirectory);
            $projectDirectory = $projectDirectory ?: $workingDirectory;
            $configuration->setProjectDirectory($projectDirectory);
        }
        if (!Helper::isAbsolutePath($configuration->getProjectDirectory())) {
            $projectDirectory = $configuration->getWorkingDirectory() .
                DIRECTORY_SEPARATOR .
                $configuration->getProjectDirectory();
            $configuration->setProjectDirectory($projectDirectory);
        }
        if (!$configuration->getDataDirectory()) {
            $configuration->setDataDirectory(Helper::getDefaultDataDirectory());
        }
        $logger = $configuration->getLogger();
        $configuration->setLogger($this->normalizeLoggerConfiguration($logger));
        return $configuration;
    }

    private function normalizeLoggerConfiguration(
        LoggerConfiguration $configuration
    ) {
        $configuration = $configuration ?: new LoggerConfiguration();
        if (!$configuration->getTarget()) {
            $configuration->setTarget(Defaults::LOGGER_TARGET);
        }
        if (!$configuration->getLevel()) {
            $configuration->setLevel(Defaults::LOGGER_LEVEL);
        }
        if (!$configuration->getFormat()) {
            $configuration->setFormat(Defaults::LOGGER_FORMAT);
        }
        $format = rtrim($configuration->getFormat()) . PHP_EOL;
        $configuration->setFormat($format);
        if (!$configuration->getPrefix()) {
            $configuration->setPrefix(Defaults::LOGGER_PREFIX);
        }
        return $configuration;
    }
}
