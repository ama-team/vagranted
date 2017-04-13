<?php

namespace AmaTeam\Vagranted\Template;

/**
 * @author Etki <etki@etki.me>
 */
interface LoaderInterface
{
    /**
     * Loads template or throws exception.
     *
     * @param string $uri
     * @return string
     */
    public function load($uri);

    /**
     * Tells whether template is loadable.
     *
     * @param string $uri
     * @return boolean
     */
    public function handles($uri);

    /**
     * Tells whether template truly exists.
     *
     * @param string $uri
     * @return boolean
     */
    public function exists($uri);
}
