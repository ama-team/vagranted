<?php

namespace AmaTeam\Vagranted\Twig;

use AmaTeam\Vagranted\Filesystem\Structure;
use AmaTeam\Vagranted\Model\Compilation\Context;
use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use Twig_Environment as Twig;
use Twig_ExtensionInterface as Extension;

/**
 * @author Etki <etki@etki.me>
 */
class Factory
{
    /**
     * @var AccessorInterface
     */
    private $filesystem;

    /**
     * @var Structure
     */
    private $structure;

    /**
     * @var Extension[]
     */
    private $extensions;

    /**
     * @param AccessorInterface $filesystem
     * @param Structure $structure
     */
    public function __construct(
        AccessorInterface $filesystem,
        Structure $structure
    ) {
        $this->filesystem = $filesystem;
        $this->structure = $structure;
    }

    /**
     * Creates new Twig instance.
     *
     * @param Context $context
     * @return Twig
     */
    public function create(Context $context)
    {
        $loader = new ContextualLoader($this->filesystem, $context);
        $twig = new Twig($loader, [
            'strict_variables' => true,
            'debug' => true,
        ]);
        $twig->setExtensions($this->extensions);
        return $twig;
    }

    /**
     * @param Extension $extension
     * @return $this
     */
    public function addExtension(Extension $extension)
    {
        $this->extensions[get_class($extension)] = $extension;
        return $this;
    }

    /**
     * @param string $class Class of the extension to remove.
     * @return Extension|null Removed extension (if any)
     */
    public function removeExtension($class)
    {
        if (isset($this->extensions[$class])) {
            $extension = $this->extensions[$class];
            unset($this->extensions[$class]);
            return $extension;
        }
        return null;
    }
}
