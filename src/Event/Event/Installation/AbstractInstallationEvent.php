<?php

namespace AmaTeam\Vagranted\Event\Event\Installation;

use AmaTeam\Vagranted\Model\Installation\Installation;
use Symfony\Component\EventDispatcher\Event;

/**
 * @author Etki <etki@etki.me>
 */
abstract class AbstractInstallationEvent extends Event
{
    /**
     * @var Installation
     */
    private $installation;

    /**
     * @param Installation $installation
     */
    public function __construct(Installation $installation)
    {
        $this->installation = $installation;
    }

    /**
     * @return Installation
     */
    public function getInstallation()
    {
        return $this->installation;
    }
}
