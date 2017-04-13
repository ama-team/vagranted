<?php

namespace AmaTeam\Vagranted\Template;

use AmaTeam\Vagranted\Helper\FilesystemHelper;
use Twig_LoaderInterface;

/**
 * Loads file looking in provided root directory, then one level up, two levels
 * up, as much as possible levels up until finds the template or hits the root.
 *
 * @author Etki <etki@etki.me>
 */
class HierarchyTraversingLoader implements Twig_LoaderInterface
{
    /**
     * @var string
     */
    private $rootDirectory;
    /**
     * @var FilesystemHelper
     */
    private $filesystemHelper;

    public function getSourceContext($name)
    {
        $path = $this->locate($name);
        if (!$path) {
            throw new \Twig_Error_Loader('Could not find file ' . $name);
        }
    }

    public function getCacheKey($name)
    {
        return $name;
    }

    public function isFresh($name, $time)
    {
        return false;
    }

    public function exists($name)
    {
        return (boolean) $this->locate($name);
    }

    private function locate($name)
    {
        $filter = function ($path) use ($name) {
            return file_exists($path . DIRECTORY_SEPARATOR . $name);
        };
        $candidates = $this
            ->filesystemHelper
            ->unroll($this->rootDirectory, $filter);
        return $candidates ? $candidates[0] : null;
    }
}
