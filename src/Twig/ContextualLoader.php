<?php

namespace AmaTeam\Vagranted\Twig;

use AmaTeam\Vagranted\Application\Configuration\Defaults;
use AmaTeam\Vagranted\Filesystem\Helper;
use AmaTeam\Vagranted\Model\Compilation\Context;
use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use Twig_Error_Loader;
use Twig_LoaderInterface;
use Twig_Source;

/**
 * @author Etki <etki@etki.me>
 */
class ContextualLoader implements Twig_LoaderInterface
{
    /**
     * @var AccessorInterface
     */
    private $filesystem;

    /**
     * @var Context
     */
    private $context;

    /**
     * @param AccessorInterface $filesystem
     * @param Context $context
     */
    public function __construct(AccessorInterface $filesystem, Context $context)
    {
        $this->filesystem = $filesystem;
        $this->context = $context;
    }

    /**
     * @inheritdoc
     */
    public function getSourceContext($name)
    {
        $paths = $this->computePathVariants($name);
        foreach ($paths as $path) {
            if (!$this->filesystem->exists($path)) {
                continue;
            }
            $code = $this->filesystem->get($path);
            return new Twig_Source($code, $this->normalize($name), $path);
        }
        throw new Twig_Error_Loader('Failed to load template ' . $name);
    }

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param string $name The name of the template to load
     *
     * @return string The cache key
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    public function getCacheKey($name)
    {
        return md5($this->normalize($name));
    }

    /**
     * Returns true if the template is still fresh.
     *
     * @param string $name The template name
     * @param int $time Timestamp of the last modification time of the
     *                     cached template
     *
     * @return bool true if the template is fresh, false otherwise
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    public function isFresh($name, $time)
    {
        return false;
    }

    /**
     * Check if we have the source code of a template, given its name.
     *
     * @param string $name The name of the template to check if we can load
     *
     * @return bool If the template source code is handled by this loader or not
     */
    public function exists($name)
    {
        $paths = $this->computePathVariants($name);
        return array_reduce($paths, function ($carrier, $path) {
            return $carrier ?: $this->filesystem->exists($path);
        }, false);
    }

    private function normalize($name)
    {
        if (strpos($name, ':') !== false) {
            return $name;
        }
        return Defaults::ROOT_PROJECT_NAME . ':' . $name;
    }

    private function computePathVariants($name)
    {
        if (Helper::isAbsolutePath($name)) {
            return [$name];
        }
        $name = $this->normalize($name);
        $delimiter = strpos($name, ':');
        $setId = substr($name, 0, $delimiter);
        $templatePath = substr($name, $delimiter);
        $sets = $this->context->getResourceSets();
        if (!isset($sets[$setId])) {
            return [];
        }
        $set = $sets[$setId];
        return [$set->getWorkspace()->getPath($templatePath)];
    }
}
