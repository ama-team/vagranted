<?php

namespace AmaTeam\Vagranted\Application\Configuration;

use Psr\Log\LogLevel;

/**
 * @author Etki <etki@etki.me>
 */
class Defaults
{
    const ROOT_PROJECT_NAME = '_root';

    const LOGGER_TARGET = 'php://stderr';
    const LOGGER_LEVEL = LogLevel::WARNING;
    const LOGGER_PREFIX = 'vagranted.';
    const LOGGER_FORMAT = '%datetime% %level_name% %channel%: %message%' ;
    const NAMESPACES = ['AmaTeam\\Vagranted\\'];
}
