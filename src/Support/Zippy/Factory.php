<?php

namespace AmaTeam\Vagranted\Support\Zippy;

use Alchemy\Zippy\Zippy;
use AmaTeam\Vagranted\Model\ConfigurationInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * @author Etki <etki@etki.me>
 */
class Factory implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const PREFIX = 'zippy.';

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
     * @param string[] $configuration
     * @return Zippy
     */
    public function create(array $configuration = [])
    {
        $zippy = Zippy::load();
        foreach ($this->configuration->getExtras() as $key => $value) {
            if (!strpos($key, self::PREFIX) === 0) {
                continue;
            }
            $key = substr($key, strlen(self::PREFIX));
            $this->apply($zippy, $key, $value);
        }
        foreach ($configuration as $adapter => $path) {
            $this->apply($zippy, $adapter, $path);
        }
        return $zippy;
    }

    /**
     * @param Zippy $zippy
     * @param string $adapter
     * @param string $path
     */
    private function apply(Zippy $zippy, $adapter, $path)
    {
        if (isset($zippy->adapters[$adapter])) {
            $zippy->adapters[$adapter] = $path;
            return;
        }
        $this->logger->warning(
            'Received unexpected option for zippy: {adapter} = {path}',
            ['adapter' => $adapter, 'path' => $path,]
        );
    }
}
