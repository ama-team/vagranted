<?php

namespace AmaTeam\Vagranted\Support\Symfony\Serializer;

use AmaTeam\Pathetic\Path;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Etki <etki@etki.me>
 */
class PathNormalizer implements NormalizerInterface
{
    public function normalize($object, $format = null, array $context = array())
    {
        return (string) $object;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Path;
    }
}
