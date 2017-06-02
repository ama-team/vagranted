<?php

namespace AmaTeam\Vagranted\Tests\Suite\System\ResourceSet\Configuration;

use AmaTeam\Pathetic\Path;
use AmaTeam\Vagranted\Filesystem\Accessor;
use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use AmaTeam\Vagranted\ResourceSet\Configuration\Reader;
use AmaTeam\Vagranted\Tests\Support\Test;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Yaml\Yaml;
use VirtualFileSystem\FileSystem as VFS;

/**
 * @author Etki <etki@etki.me>
 */
class ReaderTest extends Test
{

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var VFS
     */
    private $vfs;

    /**
     * @var AccessorInterface
     */
    private $filesystem;

    /**
     * @var Serializer
     */
    private $serializer;

    public function _before()
    {
        $this->vfs = new VFS();
        $this->filesystem = new Accessor(new Filesystem());
        $this->serializer = new Serializer(
            [new ObjectNormalizer(),],
            [new YamlEncoder(),]
        );
        $this->reader = new Reader($this->filesystem, $this->serializer);
    }

    private function path($path)
    {
        return Path::parse($this->vfs->path($path));
    }

    /**
     * @test
     */
    public function shouldHandleMissingConfiguration()
    {
        $path = $this->path('/vagranted/resource-set/vagranted.yml');
        $configuration = $this->reader->read($path);
        $this->assertNotNull($configuration);
        $this->assertNull($configuration->getName());
        $this->assertNull($configuration->getDescription());
        $this->assertEquals([], $configuration->getDependencies());
        $this->assertEquals([], $configuration->getContext());
        $this->assertEquals([], $configuration->getTemplates());
        $this->assertEquals([], $configuration->getAssets());
    }

    /**
     * @test
     */
    public function shouldNormalizeAsExpected()
    {
        $path = $this->path('/vagranted/resource-set/vagranted.yml');
        $templates = [
            '**/*.twig',
            [
                'pattern' => '**/*.twig',
                'exclusions' => [
                    'Vagrantfile.twig',
                ],
                'name' => '{{ basename }}',
            ]
        ];
        $assets = [
            '**/*.yml',
            [
                'pattern' => '**/*.yml',
                'exclusions' => [
                    'vagranted.yml',
                ],
            ],
        ];
        $raw = [
            'dependencies' => [],
            'templates' => $templates,
            'assets' => $assets,
        ];
        $this->filesystem->set($path, Yaml::dump($raw));
        $configuration = $this->reader->read($path);
        $this->assertEquals(
            $configuration->getAssets()[0]->getPattern(),
            $assets[0]
        );
        $this->assertEquals(
            $configuration->getAssets()[1]->getPattern(),
            $assets[1]['pattern']
        );
        $this->assertEquals(
            $configuration->getAssets()[1]->getExclusions(),
            $assets[1]['exclusions']
        );
        $this->assertEquals(
            $configuration->getTemplates()[0]->getPattern(),
            $templates[0]
        );
        $this->assertEquals(
            $configuration->getTemplates()[1]->getPattern(),
            $templates[1]['pattern']
        );
        $this->assertEquals(
            $configuration->getTemplates()[1]->getExclusions(),
            $templates[1]['exclusions']
        );
        $this->assertEquals(
            $configuration->getTemplates()[1]->getName(),
            $templates[1]['name']
        );
    }
}
