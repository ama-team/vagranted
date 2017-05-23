<?php

namespace AmaTeam\Vagranted\DI\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Silly base for nearly-identical method-based injection passes.
 *
 * @author Etki <etki@etki.me>
 */
abstract class AbstractMethodInjectionPass implements CompilerPassInterface
{
    /**
     * @return string
     */
    abstract protected function getServiceId();

    /**
     * @return string
     */
    abstract protected function getInjectedTag();

    /**
     * @return string
     */
    abstract protected function getInjectionMethod();

    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition($this->getServiceId());
        $injections = $container->findTaggedServiceIds($this->getInjectedTag());
        foreach (array_keys($injections) as $id) {
            $definition->addMethodCall(
                $this->getInjectionMethod(),
                [new Reference($id),]
            );
        }
    }
}
