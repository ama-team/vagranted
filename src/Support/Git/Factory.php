<?php

namespace AmaTeam\Vagranted\Support\Git;

use AmaTeam\Vagranted\Model\ConfigurationInterface;
use PHPGit\Git;

/**
 * @author Etki <etki@etki.me>
 */
class Factory
{
    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    /**
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
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
        $extras = $this->configuration->getExtras();
        return isset($extras['git.binary']) ? $extras['git.binary'] : 'git';
    }
}
