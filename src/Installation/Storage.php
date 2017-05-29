<?php

namespace AmaTeam\Vagranted\Installation;

use AmaTeam\Vagranted\Event\EventDispatcherAwareInterface;
use AmaTeam\Vagranted\Event\EventDispatcherAwareTrait;
use AmaTeam\Vagranted\Filesystem\Structure;
use AmaTeam\Vagranted\Language\MappingIterator;
use Iterator;
use Psr\Log\LoggerAwareTrait;
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
        $path = $this->composePath($name);
        $context = ['name' => $name, 'path' => $path,];
        $this->logger->debug(
            'Retrieving workspace `{name}` (path: `{path}`)',
            $context
        );
        if (!$this->exists($name)) {
            $this->logger->debug(
                'Workspace `{name}` doesn\'t exist, returning null',
                $context
            );
            return null;
        }
        return new Workspace($path);
    }

    public function create($name)
    {
        $path = $this->composePath($name);
        $context = ['name' => $name, 'path' => $path,];
        $this->logger->debug(
            'Creating workspace `{name}` (path: `{path}`)',
            $context
        );
        $this->filesystem->delete($path);
        if (!$this->filesystem->createDirectory($path)) {
            $this->logger->error(
                'Failed to create workspace `{name}` (path: `{path}`)',
                $context
            );
        }
        return new Workspace($path);
    }

    public function purge($name)
    {
        $path = $this->composePath($name);
        $context = ['name' => $name, 'path' => $path,];
        $this->logger->debug(
            'Purging workspace `{name}` (path: `{path}`)',
            $context
        );
        if (!$this->filesystem->exists($path)) {
            $this->logger->debug(
                'Workspace `{name}` (path: `{path}`) hasn\'t been created',
                $context
            );
            return false;
        }
        $this->filesystem->delete($path);
        return true;
    }

    /**
     * @return Iterator<WorkspaceInterface>
     */
    public function enumerate()
    {
        $this->logger->debug('Enumerating existing workspaces');
        $directory = $this->structure->getInstallationDirectory();
        $iterator = $this->filesystem->enumerate($directory);
        $mapper = function (SplFileInfo $entry) {
            return new Workspace($entry->getPathname());
        };
        return new MappingIterator($iterator, $mapper);
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
