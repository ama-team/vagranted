<?php

namespace AmaTeam\Vagranted\Installation\Aspect;

use AmaTeam\Vagranted\Application\Configuration\Constants;
use AmaTeam\Vagranted\Model\Installation\Installation;
use AmaTeam\Vagranted\Model\Installation\AspectLoaderInterface;
use AmaTeam\Vagranted\Model\ResourceSet\InstalledResourceSet;
use AmaTeam\Vagranted\ResourceSet\Reader;

/**
 * @author Etki <etki@etki.me>
 */
class ResourceSetLoader implements AspectLoaderInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param Installation $installation
     * @return Installation
     */
    public function load(Installation $installation)
    {
        return $this->process($installation);
    }

    /**
     * @param Installation $installation
     * @return Installation
     */
    public function bootstrap(Installation $installation)
    {
        return $this->process($installation);
    }

    /**
     * @param Installation $installation
     * @return Installation
     */
    private function process(Installation $installation)
    {
        $path = $installation
            ->getWorkspace()
            ->getPath(Constants::INSTALLATION_ARTIFACT_DIRECTORY);
        $set = $this->reader->read($path);
        $wrapper = (new InstalledResourceSet($set))
            ->setInstallation($installation);
        return $installation->setSet($wrapper);
    }
}
