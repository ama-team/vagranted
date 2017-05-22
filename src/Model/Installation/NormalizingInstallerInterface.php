<?php

namespace AmaTeam\Vagranted\Model\Installation;

/**
 * @author Etki <etki@etki.me>
 */
interface NormalizingInstallerInterface extends InstallerInterface
{
    /**
     * Normalizes uri so things like /var/../var and /var would be the very
     * same thing.
     *
     * @param string $uri
     * @return string
     */
    public function normalize($uri);
}
