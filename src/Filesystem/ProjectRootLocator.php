<?php

namespace AmaTeam\Vagranted\Filesystem;

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
     * @param string $workingDirectory
     * @return string
     */
    public function locate($workingDirectory)
    {
        foreach (Helper::unroll($workingDirectory) as $candidate) {
            // todo hardcode
            $path = $candidate . DIRECTORY_SEPARATOR . 'vagranted.yml';
            if ($this->filesystem->exists($path)) {
                return $candidate;
            }
        }
        return null;
    }
}
