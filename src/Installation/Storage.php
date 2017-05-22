<?php

namespace AmaTeam\Vagranted\Installation;

use AmaTeam\Vagranted\Event\EventDispatcherAwareInterface;
use AmaTeam\Vagranted\Event\EventDispatcherAwareTrait;
use AmaTeam\Vagranted\Filesystem\Structure;
use AmaTeam\Vagranted\Language\MappingIterator;
use AmaTeam\Vagranted\Logger\LoggerAwareTrait;
use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use AmaTeam\Vagranted\Model\Filesystem\Workspace;
use Psr\Log\LoggerAwareInterface;
use SplFileInfo;

/**
 * @author Etki <etki@etki.me>
 */
class Storage implements LoggerAwareInterface, EventDispatcherAwareInterface
{
    use LoggerAwareTrait;
    use EventDispatcherAwareTrait;

    /**
     * @var Structure
     */
    private $structure;

    /**
     * @var AccessorInterface
     */
    private $filesystem;

    /**
     * @param Structure $structure
     * @param AccessorInterface $filesystem
     */
    public function __construct(Structure $structure, AccessorInterface $filesystem)
    {
        $this->structure = $structure;
        $this->filesystem = $filesystem;
    }

    public function get($name)
    {
        if (!$this->exists($name)) {
            return null;
        }
        return new Workspace($this->composePath($name));
    }

    public function create($name)
    {
        $path = $this->composePath($name);
        $this->filesystem->delete($path);
        $this->filesystem->createDirectory($path);
        return new Workspace($path);
    }

    public function purge($name)
    {
        $path = $this->composePath($name);
        if (!$this->filesystem->exists($path)) {
            return false;
        }
        $this->filesystem->delete($path);
        return true;
    }

    public function enumerate()
    {
        $directory = $this->structure->getInstallationDirectory();
        $iterator = $this->filesystem->enumerate($directory);
        return new MappingIterator($iterator, function (SplFileInfo $entry) {
            return new Workspace($entry->getPathname());
        });
    }

    public function exists($name)
    {
        return $this->filesystem->exists($this->composePath($name));
    }

    private function composePath($name)
    {
        return $this->structure->getInstallationDirectory() .
            DIRECTORY_SEPARATOR .
            $name;
    }
}
