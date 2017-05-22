<?php

namespace AmaTeam\Vagranted\Installation;

use AmaTeam\Vagranted\Model\Installation\Installation;
use AmaTeam\Vagranted\Model\Installation\AspectLoaderInterface;

/**
 * @author Etki <etki@etki.me>
 */
class Loader
{
    /**
     * @var AspectLoaderInterface[]
     */
    private $loaders = [];

    /**
     * @param Installation $installation
     * @return Installation
     */
    public function load(Installation $installation)
    {
        foreach ($this->loaders as $loader) {
            $loader->load($installation);
        }
        return $installation;
    }

    /**
     * @param Installation $installation
     * @return Installation
     */
    public function bootstrap(Installation $installation)
    {
        foreach ($this->loaders as $loader) {
            $loader->bootstrap($installation);
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
