<?php

namespace AmaTeam\Vagranted\Application;

use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use AmaTeam\Vagranted\Filesystem\Structure;
use Symfony\Component\Serializer\Serializer;

/**
 * @author Etki <etki@etki.me>
 */
class VersionProvider
{
    /**
     * @var Structure
     */
    private $structure;

    /**
     * @var AccessorInterface
     */
    private $filesystem;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param Structure $structure
     * @param AccessorInterface $filesystem
     * @param Serializer $serializer
     */
    public function __construct(
        Structure $structure,
        AccessorInterface $filesystem,
        Serializer $serializer
    ) {
        $this->structure = $structure;
        $this->filesystem = $filesystem;
        $this->serializer = $serializer;
    }

    /**
     * Returns current project version.
     *
     * @return string
     */
    public function getVersion()
    {
        $path = $this
            ->structure
            ->getSourceDirectory()
            ->resolve('composer.json');
        $contents = $this->filesystem->get($path);
        $data = $this->serializer->decode($contents, 'json');
        return isset($data['version']) ? $data['version'] : null;
    }
}
