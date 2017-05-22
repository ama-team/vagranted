<?php

namespace AmaTeam\Vagranted\DI;

/**
 * Enumeration of well-known DI tags.
 *
 * @author Etki <etki@etki.me>
 */
class Tags
{
    const LOGGER_HANDLER = 'vagranted.logger.handler';
    const LOGGER_PROCESSOR = 'vagranted.logger.processor';
    const LOGGER_CONSUMER = 'vagranted.logger.consumer';

    const SERIALIZATION_NORMALIZER = 'vagranted.io.serializer.normalizer';
    const SERIALIZATION_ENCODER = 'vagranted.io.serializer.encoder';

    const INSTALLER = 'vagranted.resource_set.installer';
    const ASPECT_LOADER = 'vagranted.installation.aspect_loader';

    const ASPECT_COMPILER = 'vagranted.compilation.aspect_compiler';

    const EVENT_SUBSCRIBER = 'vagranted.event.subscriber';
    const EVENT_PRODUCER = 'vagranted.event.producer';

    const RECONFIGURABLE = 'vagranted.reconfigurable';

    const CONSOLE_COMMAND = 'vagranted.cli.command';

    const TWIG_EXTENSION = 'vagranted.twig.extension';
}
