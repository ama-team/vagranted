<?php

namespace AmaTeam\Vagranted\Model\Filesystem;

use AmaTeam\Pathetic\Path;
use Iterator;
use SplFileInfo;

/**
 * This interface is a temporary solution to abstract away direct fs function
 * calls.
 *
 * It should be substituted with another library as soon as suitable library is
 * found.
 *
 * @author Etki <etki@etki.me>
 */
interface AccessorInterface
{
    /**
     * @param Path $path
     * @return SplFileInfo
     */
    public function inspect(Path $path);

    /**
     * @param Path $path
     * @return string|null
     */
    public function get(Path $path);

    /**
     * @param Path $path
     * @param string $contents
     * @return void
     */
    public function set(Path $path, $contents);

    /**
     * @param Path $path
     * @return boolean
     */
    public function delete(Path $path);

    /**
     * @param Path $path
     * @param bool $recursive
     * @return Iterator Contains SplFileInfo instances
     */
    public function enumerate(Path $path, $recursive = false);

    /**
     * @param Path $path
     * @return boolean
     */
    public function exists(Path $path);

    /**
     * @param Path $path
     * @return boolean
     */
    public function createDirectory(Path $path);

    /**
     * Copies file to new location.
     *
     * @param Path $source
     * @param Path $target
     * @return void
     */
    public function copy(Path $source, Path $target);
}
