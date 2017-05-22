<?php

namespace AmaTeam\Vagranted\DI\Pass\Logger;

use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\DI\Tags;
use AmaTeam\Vagranted\Logger\FactoryInterface;
use AmaTeam\Vagranted\Logger\NameFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Etki <etki@etki.me>
 */
class LoggerInjectionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        /** @var FactoryInterface $loggerFactory */
        $loggerFactory = $container->get(References::LOGGER_FACTORY);
        /** @var NameFactory $nameFactory */
        $nameFactory = $container->get(References::LOGGER_NAME_FACTORY);
        $consumers = $container->findTaggedServiceIds(Tags::LOGGER_CONSUMER);
        foreach (array_keys($consumers) as $id) {
            $definition = $container->getDefinition($id);
            $loggerName = $nameFactory->convert($definition->getClass() ?: $id);
            $definition->addMethodCall(
                'setLogger',
                [$loggerFactory->create($loggerName)]
            );
        }
    }
}
