<?php

namespace AmaTeam\Vagranted\Application;

/**
 * Shortcuts for building library paths.
 *
 * @author Etki <etki@etki.me>
 */
class Structure
{
    const RESOURCES_DIRECTORY = 'resources';
    const BUNDLED_TEMPLATES_DIRECTORY = self::RESOURCES_DIRECTORY .
            DIRECTORY_SEPARATOR . 'template' .
            DIRECTORY_SEPARATOR . 'vagrant';
    const BINARIES_DIRECTORY = 'bin';
    const SOURCES_DIRECTORY = 'src';

    /**
     * Library root
     *
     * @var string
     */
    private $root;

    /**
     * @param string $root
     */
    public function __construct($root)
    {
        $this->root = $root;
    }

    /**
     * @return string
     */
    public function getRoot()
    {
        return $this->root;
    }

    public function getResourcesDirectory()
    {
        return $this->getDirectory(self::RESOURCES_DIRECTORY);
    }

    public function getBundledTemplatesDirectory()
    {
        return $this->getDirectory(self::BUNDLED_TEMPLATES_DIRECTORY);
    }

    public function getBinariesDirectory()
    {
        return $this->getDirectory(self::BINARIES_DIRECTORY);
    }

    public function getSourcesDirectory()
    {
        return $this->getDirectory(self::SOURCES_DIRECTORY);
    }

    private function getDirectory($directoryPath)
    {
        return $this->root . DIRECTORY_SEPARATOR . $directoryPath;
    }
}
