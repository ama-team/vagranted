<?php

namespace AmaTeam\Vagranted\Compilation;

use AmaTeam\Vagranted\Model\Filesystem\WorkspaceInterface;
use AmaTeam\Vagranted\Model\Project;

/**
 * Simple interfacish wrapper to decouple compiler and context building.
 *
 * @author Etki <etki@etki.me>
 */
class Controller
{
    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * @var ContextBuilder
     */
    private $contextBuilder;

    /**
     * @param Compiler $compiler
     * @param ContextBuilder $contextBuilder
     */
    public function __construct(
        Compiler $compiler,
        ContextBuilder $contextBuilder
    ) {
        $this->compiler = $compiler;
        $this->contextBuilder = $contextBuilder;
    }

    /**
     * @param Project $project
     * @param WorkspaceInterface $target
     */
    public function compile(Project $project, WorkspaceInterface $target)
    {
        $context = $this->contextBuilder->assemble($project);
        $this->compiler->compile($context, $target);
    }
}
