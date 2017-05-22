<?php

namespace AmaTeam\Vagranted\DI\Pass\Configuration;

use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\DI\Tags;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Collects all reconfigurable services and pushes them into corresponding
 * manager.
 *
 * @author Etki <etki@etki.me>
 */
class ReconfigurableServiceCollectionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition
            = $container->getDefinition(References::CONFIGURATION_DISTRIBUTOR);
        $consumers = $container->findTaggedServiceIds(Tags::RECONFIGURABLE);
        $references = array_map(function ($id) {
            return new Reference($id);
        }, array_keys($consumers));
        $definition->setArguments([$references]);
    }
}
