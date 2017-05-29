<?php

namespace AmaTeam\Vagranted\ResourceSet;

use AmaTeam\Vagranted\Application\Configuration\Constants;
use AmaTeam\Vagranted\Installation\Manager;
use AmaTeam\Vagranted\Model\ResourceSet\ResourceSetInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * @author Etki <etki@etki.me>
 */
class Loader implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Manager
     */
    private $manager;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param Manager $manager
     * @param Reader $reader
     */
    public function __construct(
        Manager $manager,
        Reader $reader
    ) {
        $this->manager = $manager;
        $this->reader = $reader;
    }

    /**
     * Loads resource set by uri.
     *
     * @param string $uri
     * @return ResourceSetInterface
     */
    public function load($uri)
    {
        $schema = $this->extractSchema($uri);
        if (empty($schema) || in_array($schema, Constants::LOCAL_SCHEMAS)) {
            return $this->reader->read($this->stripSchema($uri));
        }
        $manager = $this->manager;
        $installation = $manager->get($uri) ?: $manager->install($uri);
        return $installation->getSet();
    }

    private function extractSchema($uri)
    {
        $position = strpos($uri, '://');
        // if position === 0, empty schema is returned, which is equal to null
        return $position ? substr($uri, 0, $position) : null;
    }

    private function stripSchema($uri)
    {
        $position = strpos($uri, '://');
        // if position === 0, empty schema is returned, which is equal to null
        return $position === false ? $uri : substr($uri, $position + 3);
    }
}
