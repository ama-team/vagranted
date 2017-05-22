<?php

namespace AmaTeam\Vagranted\Model\Filesystem;

/**
 * @author Etki <etki@etki.me>
 */
interface FilePatternInterface
{
    /**
     * Glob pattern that should be applied against a directory to find matching
     * files.
     *
     * @return string
     */
    public function getPattern();
}
