<?php

namespace AmaTeam\Vagranted\Filesystem;

use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use AmaTeam\Vagranted\Model\Filesystem\ExclusiveFilePatternInterface;
use AmaTeam\Vagranted\Model\Filesystem\FilePatternInterface;
use AmaTeam\Vagranted\Model\Filesystem\RenamingFilePatternInterface;
use Twig_Environment;

/**
 * @author Etki <etki@etki.me>
 */
class PatternLocator
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
     * @param string $path
     * @param FilePatternInterface $pattern
     * @return string[]
     */
    public function locate($path, FilePatternInterface $pattern)
    {
        $results = [];
        foreach ($this->filesystem->enumerate($path, true) as $absolutePath) {
            $location = Helper::relativize($absolutePath, $path);
            if (!fnmatch($pattern->getPattern(), $location)) {
                continue;
            }
            if ($this->isExcluded($location, $pattern)) {
                continue;
            }
            $results[$location] = $this->computeName($location, $pattern);
        }
        return $results;
    }

    /**
     * Locates all matching files and returns array of matches in format of
     * [source relative path => [target relative paths]]
     *
     * @param string $path
     * @param FilePatternInterface[] $patterns
     * @return string[][]
     */
    public function locateMany($path, array $patterns)
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

    private function isExcluded($location, FilePatternInterface $pattern)
    {
        if (!($pattern instanceof ExclusiveFilePatternInterface)) {
            return false;
        }
        foreach ($pattern->getExclusions() as $exclusion) {
            if (fnmatch($exclusion, $location)) {
                return true;
            }
        }
        return false;
    }

    private function computeName($location, FilePatternInterface $pattern)
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
        unset($context['dirname']);
        $template = $this->twig->createTemplate($pattern->getName());
        return $template->render($context);
    }
}
