<?php

namespace AmaTeam\Vagranted\Console;

use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\Logger\Factory;
use AmaTeam\Vagranted\API;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * @author Etki <etki@etki.me>
 */
class AbstractCommand extends Command implements ContainerAwareInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        /** @var Factory $loggerFactory */
        $loggerFactory = $this
            ->container
            ->get('vagranted.logger_factory');
        return $loggerFactory->create('cli.command');
    }

    /**
     * @return API
     */
    public function getApi()
    {
        /** @var API $api */
        $api = $this->container->get(References::API);
        return $api;
    }

    protected function getEventDispatcher()
    {
        return $this->getApi()->getEventDispatcher();
    }

    /**
     * @return Serializer
     */
    protected function getSerializer()
    {
        /** @var Serializer $serializer */
        $serializer = $this->container->get(References::SERIALIZER);
        return $serializer;
    }
}
