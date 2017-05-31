<?php

namespace AmaTeam\Vagranted\Installation\Aspect;

use AmaTeam\Vagranted\Application\Configuration\Constants;
use AmaTeam\Vagranted\Installation\Aspect\Statistics\Handler;
use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use AmaTeam\Vagranted\Model\Installation\Installation;
use AmaTeam\Vagranted\Model\Installation\AspectLoaderInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * @author Etki <etki@etki.me>
 */
class StatisticsLoader implements AspectLoaderInterface
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
        $this->serializer = $serializer;
        $this->filesystem = $filesystem;
    }

    public function load(Installation $installation)
    {
        return $this->process($installation);
    }

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
            ->resolve(Constants::INSTALLATION_STATISTICS_FILE);
        $handler = new Handler($path, $this->serializer, $this->filesystem);
        return $installation->setStatistics($handler);
    }

}
