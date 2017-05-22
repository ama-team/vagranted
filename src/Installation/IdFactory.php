<?php

namespace AmaTeam\Vagranted\Installation;

/**
 * Simple wrapper for installed resource sets id generator
 *
 * @author Etki <etki@etki.me>
 */
class IdFactory
{
    public function encodeUri($uri)
    {
        return substr(md5($uri), 0, 8);
    }

    public function getVariations($reference)
    {
        return [$this->encodeUri($reference), $reference];
    }
}
