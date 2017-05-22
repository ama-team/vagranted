<?php

namespace AmaTeam\Vagranted\Application\Configuration;

use AmaTeam\Vagranted\Event\EventDispatcherAwareInterface;
use AmaTeam\Vagranted\Event\EventDispatcherAwareTrait;
use AmaTeam\Vagranted\Logger\LoggerAwareTrait;
use AmaTeam\Vagranted\Model\Configuration;
use AmaTeam\Vagranted\Model\ReconfigurableInterface;
use Psr\Log\LoggerAwareInterface;

/**
 * Allows on-the-fly application reconfiguration.
 *
 * @author Etki <etki@etki.me>
 */
class Distributor implements EventDispatcherAwareInterface, LoggerAwareInterface
{
    use EventDispatcherAwareTrait;
    use LoggerAwareTrait;

    /**
     * @var ReconfigurableInterface[]
     */
    private $services = [];

    /**
     * @param ReconfigurableInterface[] $services
     */
    public function __construct(array $services)
    {
        $this->services = $services;
    }

    public function distribute(Configuration $configuration)
    {
        $this->logger->notice('Distributing new configuration among services');
        foreach ($this->services as $service) {
            $service->reconfigure($configuration);
            $this->logger->debug(
                'Reconfigured service {service}',
                ['service' => get_class($service),]
            );
        }
        $this->logger->info('Applied new configuration to services');
    }
}
