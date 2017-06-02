<?php

namespace AmaTeam\Vagranted\Support\Guzzle;

use AmaTeam\Vagranted\Model\ConfigurationInterface;
use GuzzleHttp\Client;

/**
 * @author Etki <etki@etki.me>
 */
class Factory
{
    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    /**
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param array $config
     * @return Client
     */
    public function create(array $config = [])
    {
        return new Client($config);
    }
}
