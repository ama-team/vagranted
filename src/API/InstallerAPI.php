<?php

namespace AmaTeam\Vagranted\API;

use AmaTeam\Vagranted\Installation\InstallerCollection;
use AmaTeam\Vagranted\Model\Installation\InstallerInterface;

/**
 * @author Etki <etki@etki.me>
 */
class InstallerAPI
{
    /**
     * @var InstallerCollection
     */
    private $installers;

    /**
     * @param InstallerCollection $installers
     */
    public function __construct(InstallerCollection $installers)
    {
        $this->installers = $installers;
    }

    /**
     * @return InstallerInterface[]
     */
    public function enumerate()
    {
        return $this->installers->enumerate();
    }

    /**
     * @param string $uri
     * @return InstallerInterface[]
     */
    public function test($uri)
    {
        return $this->installers->find($uri);
    }

    /**
     * @param $id
     * @return InstallerInterface|null
     */
    public function get($id)
    {
        return $this->installers->get($id);
    }
}
