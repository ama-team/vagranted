<?php

namespace AmaTeam\Vagranted\Application\Configuration;

use AmaTeam\Pathetic\Path;
use AmaTeam\Vagranted\Filesystem\Helper;
use AmaTeam\Vagranted\Filesystem\ProjectRootLocator;
use AmaTeam\Vagranted\Model\Configuration;
use AmaTeam\Vagranted\Model\Configuration\LoggerConfiguration;
use AmaTeam\Vagranted\Model\ConfigurationInterface;

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
     * @param ConfigurationInterface $configuration
     * @return Configuration
     */
    public function normalize(ConfigurationInterface $configuration)
    {
        $normalized = new Configuration();
        $cwd = Path::parse(getcwd());
        $workingDirectory = $configuration->getWorkingDirectory() ?: $cwd;
        $workingDirectory = $cwd->resolve($workingDirectory);
        $normalized->setWorkingDirectory($workingDirectory);

        $root = $this->projectRootLocator->locate($workingDirectory);
        $projectDirectory = $configuration->getProjectDirectory() ?: $root;
        $projectDirectory = $projectDirectory ?: $workingDirectory;
        $projectDirectory = $workingDirectory->resolve($projectDirectory);
        $normalized->setProjectDirectory($projectDirectory);

        $targetDirectory = $configuration->getTargetDirectory();
        $targetDirectory = $targetDirectory ?: $projectDirectory;
        $normalized->setTargetDirectory($targetDirectory);

        $dataDirectory = $configuration->getDataDirectory();
        $dataDirectory = $dataDirectory ?: Helper::getDefaultDataDirectory();
        $normalized->setDataDirectory($dataDirectory);

        $logger = $configuration->getLogger();
        $normalized->setLogger($this->normalizeLoggerConfiguration($logger));
        return $normalized;
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
