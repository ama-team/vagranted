<?php

namespace AmaTeam\Vagranted\Model\Filesystem;

use AmaTeam\Pathetic\Path;

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
     * @return Path
     */
    public function getPath();

    /**
     * @param Path|string $path
     * @return Path
     */
    public function resolve($path);
}
