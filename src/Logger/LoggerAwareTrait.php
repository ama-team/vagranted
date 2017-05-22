<?php

namespace AmaTeam\Vagranted\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @author Etki <etki@etki.me>
 */
trait LoggerAwareTrait
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getLogger()
    {
        if (!$this->logger) {
            $this->logger = new NullLogger();
        }
        return $this->logger;
    }
}