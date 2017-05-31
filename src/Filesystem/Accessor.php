<?php

namespace AmaTeam\Vagranted\Filesystem;

use AmaTeam\Pathetic\Path;
use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * @author Etki <etki@etki.me>
 */
class Accessor implements AccessorInterface
{
    /**
     * @var Filesystem
     */
    private $helper;

    /**
     * @param Filesystem $helper
     */
    public function __construct(Filesystem $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @inheritdoc
     */
    public function inspect(Path $path)
    {
        $location = $path->toPlatformString();
        return $this->exists($path) ? new SplFileInfo($location) : null;
    }

    public function get(Path $path)
    {
        $location = $path->toPlatformString();
        return $this->exists($path) ? file_get_contents($location) : null;
    }

    public function set(Path $path, $contents)
    {
        $this->createDirectory($path->getParent());
        file_put_contents($path->toPlatformString(), $contents);
    }

    public function delete(Path $path)
    {
        if (!$this->exists($path)) {
            return false;
        }
        $this->helper->remove($path->toPlatformString());
        return true;
    }

    public function enumerate(Path $path, $recursive = false)
    {
        $finder = (new Finder())->in($path->toPlatformString());
        if (!$recursive) {
            $finder = $finder->depth(0);
        }
        return $finder->getIterator();
    }

    public function exists(Path $path)
    {
        return file_exists($path->toPlatformString());
    }

    public function createDirectory(Path $path)
    {
        if ($this->exists($path)) {
            return false;
        }
        mkdir($path->toPlatformString(), 0777, true);
        return true;
    }

    public function copy(Path $source, Path $target)
    {
        $parent = $target->getParent();
        if ($this->exists($target) && !is_dir($target->toPlatformString())) {
            $this->delete($target);
        } else if (!$this->exists($parent)) {
            $this->createDirectory($parent);
        }
        if (is_dir($source)) {
            $this->createDirectory($target);
            $this->helper->mirror(
                $source->toPlatformString(),
                $target->toPlatformString()
            );
        } else {
            copy($source->toPlatformString(), $target->toPlatformString());
        }
    }
}
