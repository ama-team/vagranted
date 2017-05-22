<?php

namespace AmaTeam\Vagranted\Event\Subscriber;

use AmaTeam\Vagranted\Event\Event\ResourceSet\Utilized;
use AmaTeam\Vagranted\Model\ResourceSet\InstalledResourceSet;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Etki <etki@etki.me>
 */
class StatisticsSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            Utilized::NAME => 'onUtilized',
        ];
    }

    public function onUtilized(Utilized $event)
    {
        $set = $event->getResourceSet();
        if (!($set instanceof InstalledResourceSet)) {
            return;
        }
        $set->getInstallation()->getStatistics()->used();
    }
}
