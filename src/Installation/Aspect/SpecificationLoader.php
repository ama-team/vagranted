<?php

namespace AmaTeam\Vagranted\Installation\Aspect;

use AmaTeam\Vagranted\Application\Configuration\Constants;
use AmaTeam\Vagranted\Model\Exception\RuntimeException;
use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use AmaTeam\Vagranted\Model\Installation\Installation;
use AmaTeam\Vagranted\Model\Installation\AspectLoaderInterface;
use AmaTeam\Vagranted\Model\Installation\Specification;
use Symfony\Component\Serializer\Serializer;

/**
 * @author Etki <etki@etki.me>
 */
class SpecificationLoader implements AspectLoaderInterface
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


    public function load(Installation $installation)
    {
        $path = $this->getLocation($installation);
        if (!$this->filesystem->exists($path)) {
            $pattern = 'Failed to find specification in installation #%s';
            $message = sprintf($pattern, $installation->getId());
            throw new RuntimeException($message);
        }
        $contents = $this->filesystem->get($path);
        /** @var Specification $specification */
        $specification = $this
            ->serializer
            ->deserialize($contents, Specification::class, 'yaml');
        $installation->setSpecification($specification);
    }

    public function bootstrap(Installation $installation)
    {
        $contents = $this
            ->serializer
            ->serialize($installation->getSpecification(), 'yaml');

        $this->filesystem->set($this->getLocation($installation), $contents);
    }

    private function getLocation(Installation $installation)
    {
        return $installation
            ->getWorkspace()
            ->getPath(Constants::INSTALLATION_SPECIFICATION_FILE);
    }
}
