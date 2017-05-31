<?php

namespace AmaTeam\Vagranted\Tests\Suite\Unit\ResourceSet;

use AmaTeam\Pathetic\Path;
use AmaTeam\Vagranted\Installation\Manager;
use AmaTeam\Vagranted\Model\Filesystem\Workspace;
use AmaTeam\Vagranted\Model\Installation\Installation;
use AmaTeam\Vagranted\Model\ResourceSet\Configuration;
use AmaTeam\Vagranted\Model\ResourceSet\ResourceSet;
use AmaTeam\Vagranted\ResourceSet\Loader;
use AmaTeam\Vagranted\ResourceSet\Reader;
use Codeception\Test\Unit;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * @author Etki <etki@etki.me>
 */
class LoaderTest extends Unit
{
    /**
     * @var Manager|Mock
     */
    private $manager;

    /**
     * @var Reader|Mock
     */
    private $reader;

    /**
     * @var ResourceSet
     */
    private $set;

    /**
     * @var Installation
     */
    private $installation;

    /**
     * @var Loader
     */
    private $loader;

    protected function _before()
    {
        $this->manager = $this->createMock(Manager::class);
        $this->reader = $this->createMock(Reader::class);
        $this->set = (new ResourceSet())
            ->setName('test-set')
            ->setWorkspace(new Workspace(Path::parse('/')))
            ->setConfiguration(new Configuration())
            ->setTemplates([])
            ->setAssets([]);
        $this->installation = (new Installation())
            ->setId('test-set')
            ->setWorkspace(new Workspace(Path::parse('/')))
            ->setSet($this->set);
        $this->loader = new Loader($this->manager, $this->reader);
    }

    public function localPathUriProvider()
    {
        return [
            ['/var/cache/resource-set',],
            ['fs:///var/cache/resource-set',],
            ['file:///var/cache/resource-set',],
            ['local:///var/cache/resource-set',],
        ];
    }

    public function externalSetUriProvider()
    {
        return [
            ['git+ssh://git@github.com/ama-team/vagranted-php-box.git',],
            ['git+https://github.com/ama-team/vagranted-php-box.git',],
            ['hg+ssh://hg@bitbucket.org/ama-team/vagranted-php-box',],
            ['hg+https://bitbucket.org/ama-team/vagranted-php-box',],
            ['https+zip://github.com/ama-team/vagranted-php-box/master/archive.zip',],
        ];
    }

    /**
     * @test
     *
     * @dataProvider localPathUriProvider
     *
     * @param $uri
     */
    public function shouldLoadLocalSetDirectly($uri)
    {
        $this
            ->reader
            ->expects($this->atLeastOnce())
            ->method('read')
            ->willReturn($this->set);
        $this
            ->manager
            ->expects($this->never())
            ->method('get');
        $this
            ->manager
            ->expects($this->never())
            ->method('install');
        $this->assertEquals($this->set, $this->loader->load($uri));
    }

    /**
     * @test
     *
     * @dataProvider externalSetUriProvider
     *
     * @param $uri
     */
    public function shouldRequestInstallationManagerForExternalSets($uri)
    {
        $this
            ->reader
            ->expects($this->never())
            ->method('read');
        $this
            ->manager
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->installation);
        $this
            ->manager
            ->expects($this->never())
            ->method('install');
        $this->assertEquals($this->set, $this->loader->load($uri));
    }

    /**
     * @test
     *
     * @dataProvider externalSetUriProvider
     *
     * @param $uri
     */
    public function shouldRequestInstallationForMissingExternalSets($uri)
    {
        $this
            ->reader
            ->expects($this->never())
            ->method('read');
        $this
            ->manager
            ->expects($this->once())
            ->method('get')
            ->willReturn(null);
        $this
            ->manager
            ->expects($this->once())
            ->method('install')
            ->willReturn($this->installation);
        $this->assertEquals($this->set, $this->loader->load($uri));
    }
}
