<?php

namespace AmaTeam\Vagranted\DI\Pass\Event;

use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\DI\Tags;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Injects event dispatcher into all services that ask for it.
 *
 * @author Etki <etki@etki.me>
 */
class DispatcherInjectionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $reference = new Reference(References::EVENT_DISPATCHER);
        $services = $container->findTaggedServiceIds(Tags::EVENT_PRODUCER);
        foreach (array_keys($services) as $id) {
            $definition = $container->getDefinition($id);
            $definition->addMethodCall('setEventDispatcher', [$reference]);
        }
    }
}
