<?php

namespace AmaTeam\Vagranted\Logger;

use AmaTeam\Vagranted\Model\ConfigurationInterface;
use AmaTeam\Vagranted\Model\ReconfigurableInterface;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * @author Etki <etki@etki.me>
 */
class Factory implements FactoryInterface, ReconfigurableInterface
{
    /**
     * @var HandlerInterface[]
     */
    private $handlers = [];

    /**
     * @var callable[]
     */
    private $processors = [];

    /**
     * All created loggers in
     *
     * @var Logger[]
     */
    private $loggers = [];

    /**
     * @param HandlerInterface[] $handlers
     * @param callable[] $processors
     */
    public function __construct(array $handlers, array $processors)
    {
        $this->handlers = $handlers;
        $this->processors = $processors;
    }

    /**
     * @param string $name Logger name
     * @return LoggerInterface
     */
    public function create($name)
    {
        if (isset($this->loggers[$name])) {
            return $this->loggers[$name];
        }
        $logger = new Logger($name, $this->handlers, $this->processors);
        return $this->loggers[$name] = $logger;
    }

    public function setHandlers(array $handlers)
    {
        $this->handlers = $handlers;
        foreach ($this->loggers as $logger) {
            $logger->setHandlers($handlers);
        }
    }

    public function setProcessors(array $processors)
    {
        $this->processors = $processors;
        foreach ($this->loggers as $logger) {
            while ($logger->getProcessors()) {
                $logger->popProcessor();
            }
            foreach ($processors as $processor) {
                $logger->pushProcessor($processor);
            }
        }
    }

    public function reconfigure(ConfigurationInterface $configuration)
    {
        foreach ($this->handlers as $handler) {
            if ($handler instanceof AbstractHandler) {
                $handler->setLevel($configuration->getLogger()->getLevel());
            }
        }
    }
}
