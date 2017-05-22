<?php

namespace AmaTeam\Vagranted\ResourceSet\Configuration;

use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use AmaTeam\Vagranted\Model\ResourceSet\Configuration;
use AmaTeam\Vagranted\Model\ResourceSet\Configuration\AssetFilter;
use AmaTeam\Vagranted\Model\ResourceSet\Configuration\TemplateFilter;
use Symfony\Component\Serializer\Serializer;

/**
 * A simple class that reads resource set embedded configuration.
 *
 * @author Etki <etki@etki.me>
 */
class Reader
{
    /**
     * @var AccessorInterface
     */
    private $filesystem;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param AccessorInterface $filesystem
     * @param Serializer $serializer
     */
    public function __construct(
        AccessorInterface $filesystem,
        Serializer $serializer
    ) {
        $this->filesystem = $filesystem;
        $this->serializer = $serializer;
    }

    public function read($path)
    {
        if (!$this->filesystem->exists($path)) {
            return new Configuration();
        }
        $contents = $this->filesystem->get($path);
        $configuration = $this->serializer->deserialize(
            $contents,
            Configuration::class,
            'yaml'
        );
        $assets = $this->deserializePatterns(
            $configuration->getAssets(),
            AssetFilter::class
        );
        $configuration->setAssets($assets);
        $templates = $this->deserializePatterns(
            $configuration->getTemplates(),
            TemplateFilter::class
        );
        $configuration->setTemplates($templates);
        return $configuration;
    }

    private function deserializePatterns($patterns, $type)
    {
        $callback = function ($input) use ($type) {
            $input = is_string($input) ? ['pattern' => $input] : $input;
            return $this->serializer->denormalize($input, $type);
        };
        return array_map($callback, $patterns);
    }
}
