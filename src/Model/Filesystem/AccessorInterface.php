<?php

namespace AmaTeam\Vagranted\Model\Filesystem;

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
     * @param string $path
     * @return SplFileInfo
     */
    public function inspect($path);

    /**
     * @param string $path
     * @return string|null
     */
    public function get($path);

    /**
     * @param string $path
     * @param string $contents
     * @return void
     */
    public function set($path, $contents);

    /**
     * @param string $path
     * @return boolean
     */
    public function delete($path);

    /**
     * @param string $path
     * @param bool $recursive
     * @return Iterator Contains SplFileInfo instances
     */
    public function enumerate($path, $recursive = false);

    /**
     * @param string $path
     * @return boolean
     */
    public function exists($path);

    /**
     * @param string $path
     * @return boolean
     */
    public function createDirectory($path);

    /**
     * Copies file to new location.
     *
     * @param string $source
     * @param string $target
     * @return void
     */
    public function copy($source, $target);
}
