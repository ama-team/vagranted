<?php

namespace AmaTeam\Vagranted\Support\Guzzle;

use AmaTeam\Vagranted\Application\Configuration\Container;
use GuzzleHttp\Client;

/**
 * @author Etki <etki@etki.me>
 */
class Factory
{
    /**
     * @var Container
     */
    private $configuration;

    /**
     * @param Container $configuration
     */
    public function __construct(Container $configuration)
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
