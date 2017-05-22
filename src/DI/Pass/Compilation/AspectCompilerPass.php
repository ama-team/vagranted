<?php

namespace AmaTeam\Vagranted\DI\Pass\Compilation;

use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\DI\Tags;
use AmaTeam\Vagranted\Model\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Etki <etki@etki.me>
 */
class AspectCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(References::ASPECT_COMPILER_COLLECTION)) {
            $message = 'Couldn\'t find aspect compiler collection by key %s' .
                References::ASPECT_COMPILER_COLLECTION;
            throw new RuntimeException($message);
        }
        $definition
            = $container->getDefinition(References::ASPECT_COMPILER_COLLECTION);
        $implementations
            = $container->findTaggedServiceIds(Tags::ASPECT_COMPILER);
        $identifiers = array_keys($implementations);
        $references = array_map(function ($id) {
            return new Reference($id);
        }, $identifiers);
        $definition->setArguments([$references]);
    }
}
