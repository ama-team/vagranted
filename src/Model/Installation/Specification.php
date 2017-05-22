<?php

namespace AmaTeam\Vagranted\Model\Installation;

/**
 * @author Etki <etki@etki.me>
 */
class Specification
{
    /**
     * @var string
     */
    private $uri;

    /**
     * @var string
     */
    private $source;

    /**
     * Revision name that was used to install resource set. It is implied that
     * it may be just some version, scm branch, etc.
     *
     * @var string
     */
    private $revision;

    /**
     * Resolved revision - in case of scm that will be precise commit.
     *
     * @var string
     */
    private $reference;

    /**
     * Arbitrary metadata left by installer.
     *
     * @var array
     */
    private $metadata = [];

    /**
     * Schema version.
     *
     * @var int
     */
    private $version = 1;

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return $this
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return string
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * @param string $revision
     * @return $this
     */
    public function setRevision($revision)
    {
        $this->revision = $revision;
        return $this;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param array $metadata
     * @return $this
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param int $version
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }
}
