<?php

namespace AmaTeam\Vagranted\Installation;

use AmaTeam\Vagranted\Model\Installation\Installation;
use AmaTeam\Vagranted\Model\Installation\AspectLoaderInterface;
use Exception;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * @author Etki <etki@etki.me>
 */
class Loader implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var AspectLoaderInterface[]
     */
    private $loaders = [];

    /**
     * @param Installation $installation
     * @return Installation
     * @throws Exception
     */
    public function load(Installation $installation)
    {
        return $this->roll('load', $installation);
    }

    /**
     * @param Installation $installation
     * @return Installation
     * @throws Exception
     */
    public function bootstrap(Installation $installation)
    {
        return $this->roll('bootstrap', $installation);
    }

    private function roll($method, Installation $installation)
    {
        foreach ($this->loaders as $loader) {
            try {
                call_user_func([$loader, $method,], $installation);
            } catch (Exception $e) {
                $message = 'Caught exception `{exception}` during calling ' .
                    '`{loader}::{method}`: {message}';
                $this->logger->error(
                    $message,
                    [
                        'loader' => get_class($loader),
                        'method' => $method,
                        'exception' => get_class($e),
                        'message' => $e->getMessage(),
                    ]
                );
                $this->logger->debug(
                    'Trace: {trace}',
                    ['trace' => $e->getTrace(),]
                );
            }
        }
        if (isset($e)) {
            throw $e;
        }
        return $installation;
    }

    /**
     * Adds aspect loader.
     *
     * @param AspectLoaderInterface $loader
     */
    public function addAspectLoader(AspectLoaderInterface $loader)
    {
        $this->loaders[get_class($loader)] = $loader;
    }

    /**
     * Removes aaspect loader with corresponding class.
     *
     * @param string $class
     * @return AspectLoaderInterface|null
     */
    public function removeAspectLoader($class)
    {
        $loader = isset($this->loaders[$class]) ? $this->loaders[$class] : null;
        unset($this->loaders[$class]);
        return $loader;
    }
}
