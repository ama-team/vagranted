<?php

namespace AmaTeam\Vagranted\Model\ResourceSet;

use AmaTeam\Vagranted\Model\Filesystem\WorkspaceInterface;

/**
 * @author Etki <etki@etki.me>
 */
interface ResourceSetInterface
{
    /**
     * Arbitrary set name so it can be distinguished from others in logs
     *
     * @return string
     */
    public function getName();

    /**
     * @return Configuration
     */
    public function getConfiguration();

    /**
     * @return WorkspaceInterface
     */
    public function getWorkspace();

    /**
     * Resource set assets in [relative source path => [relative target paths]]
     * format.
     *
     * @return string[][]
     */
    public function getAssets();

    /**
     * Resource set templates in
     * [relative source path => [relative target paths]] format.
     *
     * @return string[][]
     */
    public function getTemplates();
}
