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
     * @param Configuration $configuration
     * @return void
     */
    public function reconfigure(Configuration $configuration);
}
