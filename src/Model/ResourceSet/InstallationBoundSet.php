<?php

namespace AmaTeam\Vagranted\Model\ResourceSet;

use AmaTeam\Vagranted\Model\Installation\Installation;

/**
 * @author Etki <etki@etki.me>
 */
interface InstallationBoundSet
{
    /**
     * @return Installation
     */
    public function getInstallation();
}
