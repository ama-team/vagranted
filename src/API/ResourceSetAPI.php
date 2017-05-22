<?php

namespace AmaTeam\Vagranted\API;

use AmaTeam\Vagranted\Installation\Manager;
use AmaTeam\Vagranted\Model\Installation\Installation;
use AmaTeam\Vagranted\ResourceSet\Loader;
use DateInterval;
use DateTime;

/**
 * @author Etki <etki@etki.me>
 */
class ResourceSetAPI
{
    /**
     * @var Manager
     */
    private $installationManager;

    /**
     * @var Loader
     */
    private $loader;

    /**
     * @param Manager $installationManager
     * @param Loader $loader
     */
    public function __construct(Manager $installationManager, Loader $loader)
    {
        $this->installationManager = $installationManager;
        $this->loader = $loader;
    }

    public function enumerate()
    {
        return $this->installationManager->enumerate();
    }

    public function get($reference)
    {
        return $this->installationManager->get($reference);
    }

    public function load($uri)
    {
        return $this->loader->load($uri);
    }

    public function install($uri)
    {
        return $this->installationManager->install($uri);
    }

    public function delete($reference)
    {
        return $this->installationManager->delete($reference);
    }

    /**
     * @param DateInterval $lifespan
     * @return Installation[]
     */
    public function evict(DateInterval $lifespan)
    {
        $filter = function (Installation $installation) use ($lifespan) {
            $statistics = $installation->getStatistics();
            $usedAt = $statistics->getUsedAt() ?: $statistics->getInstalledAt();
            if (!$usedAt) {
                return true;
            }
            $expiresAt = (new DateTime())
                ->setTimestamp($usedAt->getTimestamp())
                ->setTimezone($usedAt->getTimezone())
                ->add($lifespan);
            return $expiresAt < new DateTime();
        };
        return $this->installationManager->evict($filter);
    }
}
