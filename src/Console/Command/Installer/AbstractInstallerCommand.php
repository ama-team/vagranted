<?php

namespace AmaTeam\Vagranted\Console\Command\Installer;

use AmaTeam\Vagranted\Console\AbstractCommand;
use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\Installation\InstallerCollection;

/**
 * @author Etki <etki@etki.me>
 */
abstract class AbstractInstallerCommand extends AbstractCommand
{
    /**
     * @return InstallerCollection
     */
    protected function getInstallerCollection()
    {
        /** @var InstallerCollection $collection */
        $collection = $this
            ->getContainer()
            ->get(References::INSTALLER_COLLECTION);
        return $collection;
    }
}
