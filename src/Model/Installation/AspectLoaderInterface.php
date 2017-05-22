<?php

namespace AmaTeam\Vagranted\Model\Installation;

/**
 * @author Etki <etki@etki.me>
 */
interface AspectLoaderInterface
{
    /**
     * @param Installation $installation
     * @return Installation
     */
    public function load(Installation $installation);

    /**
     * @param Installation $installation
     * @return Installation
     */
    public function bootstrap(Installation $installation);
}
