<?php

namespace AmaTeam\Vagranted\Application\Configuration;

use AmaTeam\Vagranted\Model\Configuration;

/**
 * Simple configuration wrapper
 *
 * @author Etki <etki@etki.me>
 */
class Container
{
    /**
     * @var Normalizer
     */
    private $normalizer;

    /**
     * @var Distributor
     */
    private $manager;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @param Normalizer $normalizer
     * @param Distributor $manager
     * @param Configuration|null $defaults
     */
    public function __construct(
        Normalizer $normalizer,
        Distributor $manager,
        Configuration $defaults = null
    ) {
        $this->normalizer = $normalizer;
        $this->manager = $manager;
        $this->set($defaults ?: new Configuration());
    }

    public function set(Configuration $configuration)
    {
        $this->configuration = $this->normalizer->normalize($configuration);
        $this->manager->distribute($this->configuration);
        return $this;
    }

    public function get()
    {
        return $this->configuration;
    }
}
