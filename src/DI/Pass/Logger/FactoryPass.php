<?php

namespace AmaTeam\Vagranted\DI\Pass\Logger;

use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\DI\Tags;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Etki <etki@etki.me>
 */
class FactoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(References::LOGGER_FACTORY)) {
            return;
        }
        $definition = $container->getDefinition(References::LOGGER_FACTORY);
        $handlers = $container->findTaggedServiceIds(Tags::LOGGER_HANDLER);
        $processors = $container->findTaggedServiceIds(Tags::LOGGER_PROCESSOR);
        $callback = function ($key) {
            return new Reference($key);
        };
        $arguments = $definition->getArguments() ?: [];
        $arguments[] = array_map($callback, array_keys($handlers));
        $arguments[] = array_map($callback, array_keys($processors));
        $definition->setArguments($arguments);
    }
}
