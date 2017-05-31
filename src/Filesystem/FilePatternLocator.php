<?php

namespace AmaTeam\Vagranted\Filesystem;

use AmaTeam\Pathetic\Path;
use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use AmaTeam\Vagranted\Model\Filesystem\ExclusiveFilePatternInterface;
use AmaTeam\Vagranted\Model\Filesystem\FilePatternInterface;
use AmaTeam\Vagranted\Model\Filesystem\RenamingFilePatternInterface;
use SplFileInfo;
use Twig_Environment;

/**
 * @author Etki <etki@etki.me>
 */
class FilePatternLocator
{
    /**
     * @var AccessorInterface
     */
    private $filesystem;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @param AccessorInterface $filesystem
     * @param Twig_Environment $twig
     */
    public function __construct(
        AccessorInterface $filesystem,
        Twig_Environment $twig
    ) {
        $this->filesystem = $filesystem;
        $this->twig = $twig;
    }

    /**
     * Locates all matching files and returns array of matches in format of
     * [source relative path => target relative path].
     *
     * @param Path $root
     * @param FilePatternInterface $pattern
     * @return string[]
     */
    public function locate(Path $root, FilePatternInterface $pattern)
    {
        $results = [];
        /** @var SplFileInfo $info */
        foreach ($this->filesystem->enumerate($root, true) as $info) {
            if ($info->isDir()) {
                continue;
            }
            $path = $root->relativize($info->getPathname());
            if (!fnmatch($pattern->getPattern(), (string) $path, FNM_NOESCAPE)) {
                continue;
            }
            if ($this->isExcluded($path, $pattern)) {
                continue;
            }
            $results[(string) $path] = $this->computeName($path, $pattern);
        }
        return $results;
    }

    /**
     * Locates all matching files and returns array of matches in format of
     * [source relative path => [target relative paths]]
     *
     * @param Path $path
     * @param FilePatternInterface[] $patterns
     * @return string[][]
     */
    public function locateMany(Path $path, array $patterns)
    {
        $callback = function ($carrier, FilePatternInterface $pattern) use ($path) {
            $results = $this->locate($path, $pattern);
            foreach ($results as $source => $target) {
                if (!isset($carrier[$source])) {
                    $carrier[$source] = [];
                }
                if (!in_array($target, $carrier[$source])) {
                    $carrier[$source][] = $target;
                }
            }
            return $carrier;
        };
        return array_reduce($patterns, $callback, []);
    }

    private function isExcluded(Path $path, FilePatternInterface $pattern)
    {
        if (!($pattern instanceof ExclusiveFilePatternInterface)) {
            return false;
        }
        foreach ($pattern->getExclusions() as $exclusion) {
            if (fnmatch($exclusion, (string) $path)) {
                return true;
            }
        }
        return false;
    }

    private function computeName(Path $location, FilePatternInterface $pattern)
    {
        if (!($pattern instanceof RenamingFilePatternInterface)) {
            return $location;
        }
        $context = pathinfo($location);
        if ($context['dirname'] !== '.') {
            $context['directory'] = $context['dirname'];
        }
        $context['name'] = $context['basename'];
        $context['basename'] = $context['filename'];
        $context['filename'] = $context['name'];
        $context['path'] = Path::parse($location);
        $template = $this->twig->createTemplate($pattern->getName());
        return $template->render($context);
    }
}
