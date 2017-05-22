<?php

namespace AmaTeam\Vagranted\DI\Pass\Console;

use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\DI\Tags;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Etki <etki@etki.me>
 */
class CommandInjectionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $application
            = $container->getDefinition(References::CONSOLE_APPLICATION);
        $commands = $container->findTaggedServiceIds(Tags::CONSOLE_COMMAND);
        foreach (array_keys($commands) as $id) {
            $application->addMethodCall('add', [new Reference($id)]);
        }
    }
}
