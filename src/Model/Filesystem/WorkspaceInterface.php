<?php

namespace AmaTeam\Vagranted\Model\Filesystem;

/**
 * A stub that may be expanded later with helper methods. Represents working
 * directory.
 *
 * @author Etki <etki@etki.me>
 */
interface WorkspaceInterface
{
    /**
     * Returns path of workspace itself or (if parameter is provided) path
     * inside workspace.
     *
     * @param string $path
     *
     * @return string
     */
    public function getPath($path = null);
}
