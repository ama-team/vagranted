<?php

namespace AmaTeam\Vagranted\DI\Pass\Serialization;

use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\DI\Tags;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Etki <etki@etki.me>
 */
class SerializerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $encoders = $container->findTaggedServiceIds(Tags::SERIALIZATION_ENCODER);
        $normalizerTag = Tags::SERIALIZATION_NORMALIZER;
        $normalizers = $container->findTaggedServiceIds($normalizerTag);
        uasort($normalizers, function ($left, $right) {
            $reducer = function ($max, $item) {
                $priority = isset($item['priority']) ? $item['priority'] : 0;
                return max($max, $priority);
            };
            $leftPriority = array_reduce($left, $reducer, 0);
            $rightPriority = array_reduce($right, $reducer, 0);
            return $rightPriority - $leftPriority;
        });
        $callback = function ($id) {
            return new Reference($id);
        };
        $definition = $container->getDefinition(References::SERIALIZER);
        $definition->setArguments([
            array_map($callback, array_keys($normalizers)),
            array_map($callback, array_keys($encoders)),
        ]);
    }
}
