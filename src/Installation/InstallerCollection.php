<?php

namespace AmaTeam\Vagranted\Installation;

use AmaTeam\Vagranted\Model\Exception\RuntimeException;
use AmaTeam\Vagranted\Model\Installation\InstallerInterface;

/**
 * @author Etki <etki@etki.me>
 */
class InstallerCollection
{
    /**
     * @var InstallerInterface[]
     */
    private $installers = [];

    /**
     * @param InstallerInterface $installer
     */
    public function add(InstallerInterface $installer)
    {
        $id = $installer->getId();
        if (isset($this->installers[$id])) {
            $pattern = 'Installer with id `%s` already exists';
            throw new RuntimeException(sprintf($pattern, $id));
        }
        $this->installers[$id] = $installer;
    }

    /**
     * @param string $id
     * @return InstallerInterface|null
     */
    public function remove($id)
    {
        $exists = isset($this->installers[$id]);
        $installer = $exists ? $this->installers[$id] : null;
        unset($this->installers[$id]);
        return $installer;
    }

    /**
     * @param string $id
     * @return InstallerInterface|null
     */
    public function get($id)
    {
        return isset($this->installers[$id]) ? $this->installers[$id] : null;
    }

    /**
     * @param string $uri
     * @return InstallerInterface[]
     */
    public function find($uri)
    {
        $callback = function (InstallerInterface $installer) use ($uri) {
            return $installer->supports($uri);
        };
        return array_filter($this->installers, $callback);
    }

    /**
     * @param string $uri
     * @return InstallerInterface|null
     */
    public function findOne($uri)
    {
        $installers = $this->find($uri);
        return $installers ? current($installers) : null;
    }

    /**
     * @param string $uri
     * @return InstallerInterface
     */
    public function requireOne($uri)
    {
        $installer = $this->findOne($uri);
        if (!$installer) {
            // todo incorrect exception
            $pattern = 'Could not find installer for uri `%s`';
            throw new RuntimeException(sprintf($pattern, $uri));
        }
        return $installer;
    }

    /**
     * @return InstallerInterface[]
     */
    public function enumerate()
    {
        return $this->installers;
    }
}
