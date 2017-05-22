<?php

namespace AmaTeam\Vagranted\DI\Pass\Installation;

use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\DI\Tags;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Etki <etki@etki.me>
 */
class InstallerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $id = References::INSTALLER_COLLECTION;
        $definition = $container->getDefinition($id);
        $installers = $container->findTaggedServiceIds(Tags::INSTALLER);
        foreach (array_keys($installers) as $id) {
            $definition->addMethodCall('add', [new Reference($id),]);
        }
    }
}
