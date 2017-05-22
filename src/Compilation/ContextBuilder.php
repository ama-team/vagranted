<?php

namespace AmaTeam\Vagranted\Compilation;

use AmaTeam\Vagranted\Application\Configuration\Defaults;
use AmaTeam\Vagranted\Logger\LoggerAwareTrait;
use AmaTeam\Vagranted\Model\Compilation\Context;
use AmaTeam\Vagranted\Model\Project;
use AmaTeam\Vagranted\Model\ResourceSet\ResourceSetInterface;
use AmaTeam\Vagranted\ResourceSet\Loader;
use Psr\Log\LoggerAwareInterface;

/**
 * Loads composition
 *
 * @author Etki <etki@etki.me>
 */
class ContextBuilder implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Loader
     */
    private $loader;

    /**
     * @param Loader $loader
     */
    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param Project $project
     * @return Context
     */
    public function assemble(Project $project)
    {
        $this->logger->info('Assembling compilation context');
        $root = $project->getSet();
        $queue = $root->getConfiguration()->getDependencies();
        $sets = [Defaults::ROOT_PROJECT_NAME => $root,];
        while ($queue) {
            $id = key($queue);
            $uri = current($queue);
            if (isset($sets[$id])) {
                // already retrieved
                continue;
            }
            unset($queue[$id]);
            $this->logger->debug(
                'Retrieving dependency {id} ({uri})',
                ['id' => $id, 'uri' => $uri,]
            );
            $set = $this->loader->load($uri);
            $sets[$id] = $set;
            $dependencies = $set->getConfiguration()->getDependencies();
            foreach ($dependencies as $key => $uri) {
                if (!isset($sets[$key]) && !isset($queue[$key])) {
                    $queue[$key] = $uri;
                }
            }
        }
        return (new Context())
            ->setProject($project)
            ->setResourceSets($sets)
            ->setContext($this->computeContext($sets));
    }

    /**
     * @param ResourceSetInterface[] $sets
     * @return array
     */
    private function computeContext(array $sets)
    {
        $callback = function ($carrier, ResourceSetInterface $set) {
            $context = $set->getConfiguration()->getContext();
            $context = $context ?: [];
            return array_replace_recursive($carrier, $context);
        };
        return array_reduce(array_reverse($sets), $callback, []);
    }
}
