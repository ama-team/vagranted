<?php

namespace AmaTeam\Vagranted\Logger;

use Psr\Log\NullLogger;

/**
 * @author Etki <etki@etki.me>
 */
class NullFactory implements FactoryInterface
{
    public function create($name)
    {
        return new NullLogger();
    }
}
