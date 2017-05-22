<?php

namespace AmaTeam\Vagranted\Event\Event\Compilation;

use AmaTeam\Vagranted\Model\Compilation\Context;
use AmaTeam\Vagranted\Model\ResourceSet\ResourceSetInterface;

/**
 * @author Etki <etki@etki.me>
 */
class AspectCompiled extends AbstractCompilationEvent
{
    const NAME = 'vagranted.compilation.aspect_compiled';
    /**
     * @var ResourceSetInterface
     */
    private $resourceSet;

    /**
     * @param Context $context
     * @param ResourceSetInterface $resourceSet
     */
    public function __construct(
        Context $context,
        ResourceSetInterface $resourceSet
    ) {
        parent::__construct($context);
        $this->resourceSet = $resourceSet;
    }

    /**
     * @return ResourceSetInterface
     */
    public function getResourceSet()
    {
        return $this->resourceSet;
    }
}
