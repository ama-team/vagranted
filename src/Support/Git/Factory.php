<?php

namespace AmaTeam\Vagranted\Support\Git;

use PHPGit\Git;

/**
 * @author Etki <etki@etki.me>
 */
class Factory
{
    /**
     * @param string $repository
     * @param string $binary
     * @return Git
     */
    public function create($repository, $binary = 'git')
    {
        return (new Git())
            ->setRepository($repository)
            ->setBin($binary ?: 'git');
    }
}
