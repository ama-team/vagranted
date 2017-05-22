<?php

namespace AmaTeam\Vagranted\Model\Filesystem;

/**
 * @author Etki <etki@etki.me>
 */
class Workspace implements WorkspaceInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getPath($path = null)
    {
        return $path ? $this->path . DIRECTORY_SEPARATOR . $path : $this->path;
    }
}
