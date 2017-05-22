<?php

namespace AmaTeam\Vagranted\Model\Installation;

/**
 * @author Etki <etki@etki.me>
 */
interface DescribedInstallerInterface extends InstallerInterface
{
    /**
     * @return Description
     */
    public function getDescription();
}
