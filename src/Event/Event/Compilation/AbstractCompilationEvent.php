<?php

namespace AmaTeam\Vagranted\Event\Event\Compilation;

use AmaTeam\Vagranted\Model\Compilation\Context;
use Symfony\Component\EventDispatcher\Event;

/**
 * @author Etki <etki@etki.me>
 */
abstract class AbstractCompilationEvent extends Event
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }
}
