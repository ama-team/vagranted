<?php

namespace AmaTeam\Vagranted\Filesystem;

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
    public function inspect($path)
    {
        return $this->exists($path) ? new SplFileInfo($path) : null;
    }

    public function get($path)
    {
        return $this->exists($path) ? file_get_contents($path) : null;
    }

    public function set($path, $contents)
    {
        $this->createDirectory(dirname($path));
        file_put_contents($path, $contents);
    }

    public function delete($path)
    {
        if (!$this->exists($path)) {
            return false;
        }
        $this->helper->remove($path);
        return true;
    }

    public function enumerate($path, $recursive = false)
    {
        $finder = (new Finder())->in($path);
        if (!$recursive) {
            $finder = $finder->depth('< 1');
        }
        return $finder->getIterator();
    }

    public function exists($path)
    {
        return file_exists($path);
    }

    public function createDirectory($path)
    {
        if ($this->exists($path)) {
            return false;
        }
        mkdir($path, 0777, true);
        return true;
    }

    public function copy($source, $target)
    {
        $parent = dirname($target);
        if ($this->exists($target) && !is_dir($target)) {
            $this->delete($target);
        } else if (!$this->exists($parent)) {
            $this->createDirectory($parent);
        }
        if (is_dir($source)) {
            $this->createDirectory($target);
            $this->helper->mirror($source, $target);
        } else {
            copy($source, $target);
        }
    }
}
