<?php

namespace AmaTeam\Vagranted\Filesystem;

use AmaTeam\Pathetic\Path;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Etki <etki@etki.me>
 */
class Helper
{
    /**
     * @var Filesystem
     */
    private static $symfonyFilesystem;

    /**
     * @return Filesystem
     */
    private static function getSymfonyFilesystem()
    {
        if (!self::$symfonyFilesystem) {
            self::$symfonyFilesystem = new Filesystem();
        }
        return self::$symfonyFilesystem;
    }

    /**
     * Takes directory as an input and returns directory hierarchy as list,
     * returning input directory first, then it's parent, then grandparent,
     * etc.:
     *
     *  - /var/www/app -> [/var/www/app, /var/www, /var, /]
     *  - vendor/bin -> [vendor/bin, vendor, .]
     *
     * Optional filter allows to filter out entries that are not needed.
     *
     * @param string $directory
     * @param callable $filter
     *
     * @return string[]
     */
    public static function unroll($directory, $filter = null)
    {
        $paths = [];
        $candidate = $directory;
        while (true) {
            if (!$filter || $filter($candidate)) {
                $paths[] = $candidate;
            }
            if (dirname($candidate) === $candidate) {
                break;
            }
            $candidate = dirname($candidate);
        }
        return $paths;
    }

    /**
     * Returns Vagranted installation root.
     *
     * @return Path
     */
    public static function getInstallationRoot()
    {
        return Path::parse(__DIR__)->getParent()->getParent();
    }

    /**
     * @return Path
     */
    public static function getDefaultDataDirectory()
    {
        if (getenv('HOME')) {
            $path = getenv('HOME') . '/.cache/ama-team/vagranted';
        } else if (getenv('LocalAppData')) {
            $path = getenv('LocalAppData') . '/AMA Team/Vagranted';
        } else {
            $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'vagranted';
        }
        return Path::parse($path);
    }

    /**
     * @param string $path
     * @param string $root
     * @return string
     */
    public static function relativize($path, $root)
    {
        $path = self::normalize($path);
        $root = self::normalize($root);
        $directory = dirname($path);
        if ($directory === $root) {
            return basename($path);
        }
        $parent = self::getSymfonyFilesystem()
            ->makePathRelative($directory, $root);
        $parent = rtrim($parent, '\\/');
        return $parent . DIRECTORY_SEPARATOR . basename($path);
    }

    public static function isAbsolutePath($path)
    {
        return self::getSymfonyFilesystem()->isAbsolutePath($path);
    }

    public static function normalize($path)
    {
        $path = str_replace('\\', '/', $path);
        $parts = explode('/', $path);
        if (sizeof($parts) <= 1) {
            return implode('', $parts);
        }
        $stack = [];
        foreach ($parts as $part) {
            if ($part === '.' || $part === '') {
                continue;
            } else if ($part === '..' && !empty($stack)) {
                array_pop($stack);
                continue;
            }
            $stack[] = $part;
        }
        if ($parts[0] === '') {
            array_unshift($stack, '');
        }
        if ($parts[sizeof($parts) - 1] === '') {
            $stack[] = '';
        }
        return implode(DIRECTORY_SEPARATOR, $stack);
    }
}
