<?php

namespace AmaTeam\Vagranted\Compilation;

use AmaTeam\Vagranted\Event\Event\Compilation\AspectCompiled;
use AmaTeam\Vagranted\Event\EventDispatcherAwareInterface;
use AmaTeam\Vagranted\Event\EventDispatcherAwareTrait;
use AmaTeam\Vagranted\Model\Compilation\Context;
use AmaTeam\Vagranted\Model\Filesystem\WorkspaceInterface;
use AmaTeam\Vagranted\Model\ResourceSet\ResourceSetInterface;

/**
 * @author Etki <etki@etki.me>
 */
class AspectCompilerCollection implements
    AspectCompilerInterface,
    EventDispatcherAwareInterface
{
    use EventDispatcherAwareTrait;

    /**
     * @var AspectCompilerInterface[]
     */
    private $compilers = [];

    /**
     * @param AspectCompilerInterface[] $compilers
     */
    public function __construct(array $compilers = []) {
        $this->compilers = $compilers;
    }

    /**
     * @inheritdoc
     */
    public function compile(
        ResourceSetInterface $set,
        WorkspaceInterface $workspace,
        Context $context
    ) {
        foreach ($this->compilers as $compiler) {
            $compiler->compile($set, $workspace, $context);
            $this->eventDispatcher->dispatch(
                AspectCompiled::NAME,
                new AspectCompiled($context, $set)
            );
        }
    }

    public function add(AspectCompilerInterface $compiler)
    {
        $this->compilers[] = $compiler;
    }
}
