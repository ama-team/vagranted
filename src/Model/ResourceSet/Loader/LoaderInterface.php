<?php

namespace AmaTeam\Vagranted\Model\ResourceSet\Loader;

use AmaTeam\Vagranted\Model\ResourceSet\ResourceSetInterface;

/**
 * @author Etki <etki@etki.me>
 */
interface LoaderInterface
{
    /**
     * Loads resource set located at provided path.
     *
     * @param string $path
     * @return ResourceSetInterface
     */
    public function load($path);
}
