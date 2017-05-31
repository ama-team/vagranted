<?php

namespace AmaTeam\Vagranted\Support\Symfony\Serializer;

use AmaTeam\Vagranted\Model\ResourceSet\InstalledResourceSet;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @author Etki <etki@etki.me>
 */
class InstalledResourceSetNormalizer implements NormalizerInterface
{
    /**
     * @var ObjectNormalizer
     */
    private $normalizer;

    /**
     * @param ObjectNormalizer $normalizer
     */
    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($object, $format = null, array $context = array())
    {
        /** @var InstalledResourceSet $clone */
        $clone = clone $object;
        $installation = clone $clone->getInstallation();
        $installation->setSet(null);
        $clone->setInstallation($installation);
        return $this->normalizer->normalize($clone);
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof InstalledResourceSet;
    }
}
