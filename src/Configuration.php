<?php

namespace AmaTeam\Vagranted;

/**
 * @author Etki <etki@etki.me>
 */
class Configuration
{
    /**
     * @var string
     */
    private $workingDirectory;

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
}
