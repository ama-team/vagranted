<?php

namespace AmaTeam\Vagranted\Model\Installation;

/**
 * @author Etki <etki@etki.me>
 */
interface InstallerInterface
{
    /**
     * Unique installer ID.
     *
     * @return string
     */
    public function getId();

    /**
     * Tells whether uri may be handled by this installer.
     *
     * @param string $uri
     * @return boolean
     */
    public function supports($uri);

    /**
     * Installs resource set from provided uri in provided location.
     *
     * @param string $uri
     * @param string $path
     * @return Specification|null
     */
    public function install($uri, $path);
}
