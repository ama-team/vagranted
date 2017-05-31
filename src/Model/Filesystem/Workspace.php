<?php

namespace AmaTeam\Vagranted\Model\Filesystem;

use AmaTeam\Pathetic\Path;

/**
 * @author Etki <etki@etki.me>
 */
class Workspace implements WorkspaceInterface
{
    /**
     * @var Path
     */
    private $path;

    /**
     * @param Path $path
     */
    public function __construct(Path $path)
    {
        $this->path = $path;
    }

    /**
     * @inheritdoc
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @inheritdoc
     */
    public function resolve($path)
    {
        return $this->path->resolve($path);
    }
}
