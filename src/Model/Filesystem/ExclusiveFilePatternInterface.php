<?php

namespace AmaTeam\Vagranted\Model\Filesystem;

/**
 * @author Etki <etki@etki.me>
 */
interface ExclusiveFilePatternInterface extends FilePatternInterface
{
    /**
     * List of glob patterns that would be applied against found files to find
     * ones that should be removed from resulting set.
     *
     * @return string[]
     */
    public function getExclusions();
}
