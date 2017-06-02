<?php

namespace AmaTeam\Vagranted\DI;

/**
 * Well-known container service names.
 *
 * @author Etki <etki@etki.me>
 */
class References
{
    const BOOTSTRAP_CONFIGURATION = 'vagranted.configuration.bootstrap';
    const CONTAINER = 'vagranted.container';

    const VERSION_PROVIDER = 'vagranted.version_provider';
    const CONFIGURATION = 'vagranted.configuration';
    const CONFIGURATION_DISTRIBUTOR = 'vagranted.configuration.distributor';
    const LOGGER_FACTORY = 'vagranted.logger.factory';
    const LOGGER_NAME_FACTORY = 'vagranted.logger.name_factory';
    const INSTALLER_COLLECTION
        = 'vagranted.installation.installer_collection';
    const INSTALLATION_LOADER = 'vagranted.installation.loader';

    const SERIALIZER = 'vagranted.io.serializer';
    const EVENT_DISPATCHER = 'vagranted.event.dispatcher';
    const RESOURCE_SET_MANAGER = 'vagranted.resource_set.manager';
    const RESOURCE_SET_LOADER = 'vagranted.resource_set.loader';
    const PROJECT_LOADER = 'vagranted.project.loader';
    const ASPECT_COMPILER_COLLECTION
        = 'vagranted.compilation.aspect_compiler_collection';

    const CONSOLE_APPLICATION = 'vagranted.cli';

    const PROJECT_ROOT_LOCATOR = 'vagranted.io.filesystem.project_root_locator';

    const API = 'vagranted.api';
    const COMPILATION_API = 'vagranted.api.compilation';
    const RESOURCE_SET_API = 'vagranted.api.resource_set';
    const INSTALLER_API = 'vagranted.api.installer';
    const TWIG_INLINE = 'vagranted.twig.inline';
    const TWIG_CONTEXTUAL_FACTORY = 'vagranted.twig.contextual.factory';
}
