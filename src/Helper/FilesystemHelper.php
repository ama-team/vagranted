<?php

namespace AmaTeam\Vagranted\Helper;

/**
 * @author Etki <etki@etki.me>
 */
class FilesystemHelper
{
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
    public function unroll($directory, $filter = null)
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
}
