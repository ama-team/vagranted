<?php

namespace AmaTeam\Vagranted\Model;

use AmaTeam\Pathetic\Path;
use AmaTeam\Vagranted\Model\Configuration\LoggerConfiguration;

/**
 * @author Etki <etki@etki.me>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Directory of the project that requires vagranted support.
     *
     * @var Path
     */
    private $projectDirectory;

    /**
     * Current working directory.
     *
     * @var Path
     */
    private $workingDirectory;

    /**
     * Data directory - place where application may store it's belongings.
     *
     * @var Path
     */
    private $dataDirectory;

    /**
     * Target directory - where the result is going to be compiled.
     *
     * @var Path
     */
    private $targetDirectory;

    /**
     * @var LoggerConfiguration
     */
    private $logger;

    /**
     * Any additional data someone may want to set.
     *
     * @var array
     */
    private $extras = [];

    /**
     * Initializer
     */
    public function __construct()
    {
        $this->logger = new LoggerConfiguration();
    }

    /**
     * @return Path
     */
    public function getWorkingDirectory()
    {
        return $this->workingDirectory;
    }

    /**
     * @param Path $workingDirectory
     * @return $this
     */
    public function setWorkingDirectory(Path $workingDirectory)
    {
        $this->workingDirectory = $workingDirectory;
        return $this;
    }

    /**
     * @return Path
     */
    public function getDataDirectory()
    {
        return $this->dataDirectory;
    }

    /**
     * @param Path $dataDirectory
     * @return $this
     */
    public function setDataDirectory(Path $dataDirectory)
    {
        $this->dataDirectory = $dataDirectory;
        return $this;
    }

    /**
     * @return Path
     */
    public function getProjectDirectory()
    {
        return $this->projectDirectory;
    }

    /**
     * @param Path $projectDirectory
     * @return $this
     */
    public function setProjectDirectory(Path $projectDirectory)
    {
        $this->projectDirectory = $projectDirectory;
        return $this;
    }

    /**
     * @return Path
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    /**
     * @param Path $targetDirectory
     * @return $this
     */
    public function setTargetDirectory(Path $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
        return $this;
    }

    /**
     * @return LoggerConfiguration
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerConfiguration $logger
     * @return $this
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return array
     */
    public function getExtras()
    {
        return $this->extras;
    }

    /**
     * @param array $extras
     * @return $this
     */
    public function setExtras($extras)
    {
        $this->extras = $extras;
        return $this;
    }
}
