<?php

namespace AmaTeam\Vagranted\Model\ResourceSet;

use AmaTeam\Vagranted\Model\Installation\Installation;

/**
 * @author Etki <etki@etki.me>
 */
class InstalledResourceSet extends ResourceSetWrapper implements
    InstallationBoundSet
{
    /**
     * @var Installation
     */
    private $installation;

    public function getName()
    {
        return $this->installation->getId();
    }

    /**
     * @return Installation
     */
    public function getInstallation()
    {
        return $this->installation;
    }

    /**
     * @param Installation $installation
     * @return $this
     */
    public function setInstallation($installation)
    {
        $this->installation = $installation;
        return $this;
    }
}
