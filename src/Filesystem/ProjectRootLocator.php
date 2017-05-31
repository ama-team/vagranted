<?php

namespace AmaTeam\Vagranted\Filesystem;

use AmaTeam\Pathetic\Path;
use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;

/**
 * Locates project root using current working directory.
 *
 * @author Etki <etki@etki.me>
 */
class ProjectRootLocator
{
    /**
     * @var AccessorInterface
     */
    private $filesystem;

    /**
     * @param AccessorInterface $filesystem
     */
    public function __construct(AccessorInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param Path $workingDirectory
     * @return string
     */
    public function locate(Path $workingDirectory)
    {
        /** @var Path[] $candidates */
        $candidates = array_reverse($workingDirectory->enumerate());
        foreach ($candidates as $candidate) {
            // todo hardcode
            $path = $candidate->resolve('vagranted.yml');
            if ($this->filesystem->exists($path)) {
                return $candidate;
            }
        }
        return null;
    }
}
