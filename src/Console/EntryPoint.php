<?php

namespace AmaTeam\Vagranted\Console;

use AmaTeam\Vagranted\Builder;
use Symfony\Component\Console\Input\ArgvInput;

/**
 * @author Etki <etki@etki.me>
 */
class EntryPoint
{
    public static function main(array $args)
    {
        $application = (new Builder())->build()->getConsoleApplication();
        exit($application->run(new ArgvInput($args)));
    }
}
