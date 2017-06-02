<?php

namespace AmaTeam\Vagranted\Model;

use AmaTeam\Pathetic\Path;
use AmaTeam\Vagranted\Model\Configuration\LoggerConfiguration;

/**
 * Vagranted runtime configuration.
 *
 * @author Etki <etki@etki.me>
 */
interface ConfigurationInterface
{
    /**
     * @return Path
     */
    public function getWorkingDirectory();

    /**
     * @return Path
     */
    public function getProjectDirectory();

    /**
     * @return Path
     */
    public function getTargetDirectory();

    /**
     * @return Path
     */
    public function getDataDirectory();

    /**
     * @return string[]
     */
    public function getExtras();

    /**
     * @return LoggerConfiguration
     */
    public function getLogger();
}
