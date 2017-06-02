<?php

namespace AmaTeam\Vagranted\Language;

use AmaTeam\Pathetic\Path;

/**
 * @author Etki <etki@etki.me>
 */
class Environment
{
    /**
     * Returns current working directory
     *
     * @return Path
     */
    public function getWorkingDirectory()
    {
        return Path::parse(getcwd());
    }

    /**
     * Returns environment variable.
     *
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public function getVariable($key, $default = null)
    {
        $value = getenv($key);
        return $value === false ? $default : $value;
    }

    /**
     * Returns all environment variables.
     *
     * @return string[]
     */
    public function getVariables()
    {
        return getenv();
    }
}
