<?php

namespace AmaTeam\Vagranted\Compilation;

use AmaTeam\Vagranted\Model\Compilation\Context;
use AmaTeam\Vagranted\Model\Filesystem\WorkspaceInterface;
use AmaTeam\Vagranted\Model\ResourceSet\ResourceSetInterface;

/**
 * @author Etki <etki@etki.me>
 */
interface AspectCompilerInterface
{
    /**
     * @param ResourceSetInterface $set
     * @param WorkspaceInterface $target
     * @param Context $context
     * @return void
     */
    public function compile(
        ResourceSetInterface $set,
        WorkspaceInterface $target,
        Context $context
    );
}
