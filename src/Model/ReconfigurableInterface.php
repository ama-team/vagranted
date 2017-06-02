<?php

namespace AmaTeam\Vagranted\Model;

/**
 * Describes service that is capable of on-the-fly reconfiguring
 *
 * @author Etki <etki@etki.me>
 */
interface ReconfigurableInterface
{
    /**
     * @param ConfigurationInterface $configuration
     * @return void
     */
    public function reconfigure(ConfigurationInterface $configuration);
}
