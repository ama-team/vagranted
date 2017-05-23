<?php

namespace AmaTeam\Vagranted\Installation;

use AmaTeam\Vagranted\Logger\LoggerAwareTrait;
use AmaTeam\Vagranted\Model\Installation\Installation;
use Iterator;
use Psr\Log\LoggerAwareInterface;

/**
 * This manager wraps storage controller and simplifies interface by hiding
 * identifier-issuing mechanism and allowing to reference installation by both
 * uri and id.
 *
 * Reference argument that is met in most of the methods is either URI or ID. It
 * has to exactly match URI or ID, no fuzzy match is made.
 *
 * @author Etki <etki@etki.me>
 */
class Manager implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var StorageController
     */
    private $controller;

    /**
     * @var IdFactory
     */
    private $idFactory;

    /**
     * @param StorageController $controller
     * @param IdFactory $idFactory
     */
    public function __construct(
        StorageController $controller,
        IdFactory $idFactory
    ) {
        $this->controller = $controller;
        $this->idFactory = $idFactory;
    }

    /**
     * Tells if installation for specified reference exists.
     *
     * @param string $reference
     * @return bool
     */
    public function exists($reference)
    {
        foreach ($this->idFactory->getVariations($reference) as $candidate) {
            if ($this->controller->exists($candidate)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Retrieves installation by reference.
     *
     * @param string $reference
     * @return Installation|null
     */
    public function get($reference)
    {
        foreach ($this->idFactory->getVariations($reference) as $candidate) {
            $installation = $this->controller->get($candidate);
            if ($installation !== null) {
                return $installation;
            }
        }
        return null;
    }

    /**
     * Installs resource set from URI
     *
     * @param string $uri
     * @return Installation
     */
    public function install($uri)
    {
        $uri = $this->controller->normalize($uri);
        $id = $this->idFactory->encodeUri($uri);
        return $this->controller->install($uri, $id);
    }

    /**
     * tells if provided URI is installable.
     *
     * @param string $uri
     * @return bool
     */
    public function supports($uri)
    {
        return $this->controller->supports($uri);
    }

    /**
     * Deletes resource set by reference
     *
     * @param string $reference
     * @return Installation|null
     */
    public function delete($reference)
    {
        foreach ($this->idFactory->getVariations($reference) as $candidate) {
            if ($this->controller->exists($candidate)) {
                return $this->controller->delete($candidate);
            }
        }
        return null;
    }

    /**
     * @return Installation[]|Iterator
     */
    public function enumerate()
    {
        return $this->controller->enumerate();
    }

    /**
     * @param callable $filter
     * @return Installation[]
     */
    public function evict(callable $filter)
    {
        return $this->controller->evict($filter);
    }
}
