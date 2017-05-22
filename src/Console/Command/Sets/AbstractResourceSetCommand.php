<?php

namespace AmaTeam\Vagranted\Console\Command\Sets;

use AmaTeam\Vagranted\API\ResourceSetAPI;
use AmaTeam\Vagranted\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Etki <etki@etki.me>
 */
abstract class AbstractResourceSetCommand extends AbstractCommand
{
    protected function addUriArgument()
    {
        return $this
            ->addArgument(
                'uri',
                InputArgument::REQUIRED,
                'Resource set source uri'
            );
    }

    /**
     * @return ResourceSetAPI
     */
    protected function getResourceSetAPI()
    {
        return $this
            ->getApi()
            ->getResourceSetAPI();
    }
}
