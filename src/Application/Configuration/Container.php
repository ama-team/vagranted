<?php

namespace AmaTeam\Vagranted\Application\Configuration;

use AmaTeam\Vagranted\Model\Configuration;
use AmaTeam\Vagranted\Model\ConfigurationInterface;
use AmaTeam\Vagranted\Model\ConfigurationWrapper;

/**
 * Simple configuration wrapper
 *
 * @author Etki <etki@etki.me>
 */
class Container extends ConfigurationWrapper
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
     * @param Normalizer $normalizer
     * @param Distributor $manager
     * @param Configuration|null $defaults
     */
    public function __construct(
        Normalizer $normalizer,
        Distributor $manager,
        Configuration $defaults = null
    ) {
        parent::__construct();
        $this->normalizer = $normalizer;
        $this->manager = $manager;
        $this->setEnclosure($defaults ?: new Configuration());
    }

    public function setEnclosure(ConfigurationInterface $configuration)
    {
        $configuration = $this->normalizer->normalize($configuration);
        parent::setEnclosure($configuration);
        $this->manager->distribute($this);
        return $this;
    }
}
