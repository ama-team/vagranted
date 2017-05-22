<?php

namespace AmaTeam\Vagranted\DI\Pass\Twig;

use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\DI\Tags;
use AmaTeam\Vagranted\Model\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Etki <etki@etki.me>
 */
class ExtensionCollectionPass implements CompilerPassInterface
{
    const SCOPE_CONTEXTUAL = 'contextual';
    const SCOPE_INLINE = 'inline';

    public function process(ContainerBuilder $container)
    {
        $extensions = $container->findTaggedServiceIds(Tags::TWIG_EXTENSION);
        foreach ($extensions as $id => $tags) {
            $scope = $this->extractScope($id, current($tags));
            $target = $this->findTarget($container, $scope);
            $target->addMethodCall(
                'addExtension',
                [new Reference($id),]
            );
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param string $scope
     * @return Definition
     */
    private function findTarget(ContainerBuilder $container, $scope)
    {
        if ($scope === self::SCOPE_INLINE) {
            return $container->getDefinition(References::TWIG_INLINE);
        }
        return $container->getDefinition(References::TWIG_CONTEXTUAL_FACTORY);
    }

    private function extractScope($id, $tag)
    {
        $scope = isset($tag['scope']) ? $tag['scope'] : null;
        $scopes = [self::SCOPE_INLINE, self::SCOPE_CONTEXTUAL,];
        if (!in_array($scope, $scopes)) {
            $pattern = 'Twig extension `%s` doesn\'t specify correct ' .
                'scope (expected `%s` or `%s`)';
            $message = sprintf(
                $pattern,
                $id,
                self::SCOPE_CONTEXTUAL,
                self::SCOPE_INLINE
            );
            throw new RuntimeException($message);
        }
        return $scope;
    }
}
