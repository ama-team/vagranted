<?php

namespace AmaTeam\Vagranted\Event;

/**
 * @author Etki <etki@etki.me>
 */
class Events
{
    const ResourceSetDeleted = 'resource_set.deleted';
    const ResourceSetEvicted = 'resource_set.evicted';
    const ResourceSetInstalledEvent = 'resource_set.installed';
    const ResourceSetLoadedEvent = 'resource_set.loaded';
    const ResourceSetUtilizedEvent = 'resource_set.utilized';
    const TIME_MEASUREMENT_FINISHED = 'general.time_measurement_finished';
}
