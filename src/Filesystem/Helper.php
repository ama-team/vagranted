<?php

namespace AmaTeam\Vagranted\Filesystem;

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
     * @return string
     */
    public static function getInstallationRoot()
    {
        return dirname(dirname(__DIR__));
    }

    /**
     * @return string
     */
    public static function getDefaultDataDirectory()
    {
        if (getenv('HOME')) {
            return getenv('HOME') . '/.cache/ama-team/vagranted';
        }
        if (getenv('LocalAppData')) {
            return getenv('LocalAppData') . '\\AMA Team\\Vagranted';
        }
        return sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'vagranted';
    }

    /**
     * @param string $path
     * @param string $root
     * @return string
     */
    public static function relativize($path, $root)
    {

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
}
