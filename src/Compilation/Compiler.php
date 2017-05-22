<?php

namespace AmaTeam\Vagranted\Compilation;

use AmaTeam\Vagranted\Event\Event\Compilation\Finished;
use AmaTeam\Vagranted\Event\Event\Compilation\ResourceSetProcessed;
use AmaTeam\Vagranted\Event\Event\Compilation\Started;
use AmaTeam\Vagranted\Event\Event\ResourceSet\Utilized;
use AmaTeam\Vagranted\Event\EventDispatcherAwareInterface;
use AmaTeam\Vagranted\Event\EventDispatcherAwareTrait;
use AmaTeam\Vagranted\Model\Compilation\Context;
use AmaTeam\Vagranted\Model\Filesystem\WorkspaceInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * @author Etki <etki@etki.me>
 */
class Compiler implements EventDispatcherAwareInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    use EventDispatcherAwareTrait;

    /**
     * @var AspectCompilerCollection
     */
    private $compilers;

    /**
     * @param AspectCompilerCollection $compilers
     */
    public function __construct(AspectCompilerCollection $compilers) {
        $this->compilers = $compilers;
    }

    /**
     * Builds result using provided composition at specified path.
     *
     * @param Context $context
     * @param WorkspaceInterface $target Path to directory that has to be vagranted.
     */
    public function compile(Context $context, WorkspaceInterface $target)
    {
        $path = $context->getProject()->getWorkspace()->getPath();
        $this->logger->notice(
            'Performing project `{path}` compilation to path `{target}`',
            ['path' => $path, 'target' => $target->getPath(),]
        );
        $sets = $context->getResourceSets();
        $reverseSets = array_reverse($sets);
        $this->eventDispatcher->dispatch(Started::NAME, new Started($context));
        foreach ($reverseSets as $set) {
            $this->compilers->compile($set, $target, $context);
            $this->eventDispatcher->dispatch(
                ResourceSetProcessed::NAME,
                new ResourceSetProcessed($context, $set)
            );
            $this->eventDispatcher->dispatch(
                Utilized::NAME,
                new Utilized($set)
            );
        }
        $this->eventDispatcher->dispatch(
            Finished::NAME,
            new Finished($context)
        );
        $this->logger->notice(
            'Successfully compiled project {path}',
            ['path' => $path,]
        );
    }
}
