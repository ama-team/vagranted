<?php

namespace AmaTeam\Vagranted\Model\Filesystem;

/**
 * @author Etki <etki@etki.me>
 */
interface RenamingFilePatternInterface extends FilePatternInterface
{
    /**
     * Returns name template for file that falls under pattern.
     *
     * @return string
     */
    public function getName();
}
