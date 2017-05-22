<?php

namespace AmaTeam\Vagranted\Model;

use AmaTeam\Vagranted\Model\Configuration\LoggerConfiguration;

/**
 * @author Etki <etki@etki.me>
 */
class Configuration
{
    /**
     * Directory of the project that requires vagranted support.
     *
     * @var string
     */
    private $projectDirectory;

    /**
     * Current working directory.
     *
     * @var string
     */
    private $workingDirectory;

    /**
     * Data directory - place where application may store it's belongings.
     *
     * @var string
     */
    private $dataDirectory;

    /**
     * Target directory - where the result is going to be compiled.
     *
     * @var string
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

    public function __construct()
    {
        $this->logger = new LoggerConfiguration();
    }

    /**
     * @return string
     */
    public function getWorkingDirectory()
    {
        return $this->workingDirectory;
    }

    /**
     * @param string $workingDirectory
     * @return $this
     */
    public function setWorkingDirectory($workingDirectory)
    {
        $this->workingDirectory = $workingDirectory;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataDirectory()
    {
        return $this->dataDirectory;
    }

    /**
     * @param mixed $dataDirectory
     * @return $this
     */
    public function setDataDirectory($dataDirectory)
    {
        $this->dataDirectory = $dataDirectory;
        return $this;
    }

    /**
     * @return string
     */
    public function getProjectDirectory()
    {
        return $this->projectDirectory;
    }

    /**
     * @param string $projectDirectory
     * @return $this
     */
    public function setProjectDirectory($projectDirectory)
    {
        $this->projectDirectory = $projectDirectory;
        return $this;
    }

    /**
     * @return string
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    /**
     * @param string $targetDirectory
     * @return $this
     */
    public function setTargetDirectory($targetDirectory)
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
