<?php

namespace AmaTeam\Vagranted\Support\Git;

use AmaTeam\Vagranted\Application\Configuration\Container;
use PHPGit\Git;

/**
 * @author Etki <etki@etki.me>
 */
class Factory
{
    /**
     * @var Container
     */
    private $configuration;

    /**
     * @param Container $configuration
     */
    public function __construct(Container $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param string $repository
     * @param string $binary
     * @return Git
     */
    public function create($repository, $binary = 'git')
    {
        return (new Git())
            ->setRepository($repository)
            ->setBin($binary ?: $this->computeBinary());
    }

    private function computeBinary()
    {
        $extras = $this->configuration->get()->getExtras();
        return isset($extras['git.binary']) ? $extras['git.binary'] : 'git';
    }
}
