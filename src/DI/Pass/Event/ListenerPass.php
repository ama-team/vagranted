<?php

namespace AmaTeam\Vagranted\DI\Pass\Event;

use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\DI\Tags;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Etki <etki@etki.me>
 */
class ListenerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition(References::EVENT_DISPATCHER);
        $listeners = $container->findTaggedServiceIds(Tags::EVENT_SUBSCRIBER);
        foreach (array_keys($listeners) as $listener) {
            $definition->addMethodCall(
                'addSubscriber',
                [new Reference($listener)]
            );
        }
    }
}
