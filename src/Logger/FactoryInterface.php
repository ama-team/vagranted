<?php

namespace AmaTeam\Vagranted\Logger;

use Psr\Log\LoggerInterface;

/**
 * @author Etki <etki@etki.me>
 */
interface FactoryInterface
{
    /**
     * @param string $name
     * @return LoggerInterface
     */
    public function create($name);
}
