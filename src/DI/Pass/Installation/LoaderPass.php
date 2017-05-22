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
class LoaderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition
            = $container->getDefinition(References::INSTALLATION_LOADER);
        $loaders = $container->findTaggedServiceIds(Tags::ASPECT_LOADER);
        foreach (array_keys($loaders) as $id) {
            $definition->addMethodCall(
                'addAspectLoader',
                [new Reference($id),]
            );
        }
    }
}
