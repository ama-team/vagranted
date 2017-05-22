<?php

namespace AmaTeam\Vagranted\Model\ResourceSet\Configuration;

use AmaTeam\Vagranted\Model\Filesystem\ExclusiveFilePatternInterface;
use AmaTeam\Vagranted\Model\Filesystem\RenamingFilePatternInterface;

/**
 * @author Etki <etki@etki.me>
 */
class TemplateFilter implements
    ExclusiveFilePatternInterface,
    RenamingFilePatternInterface
{
    /**
     * List of glob patterns that are used to find templates.
     *
     * @var string[]
     */
    private $pattern;

    /**
     * List of glob patterns that are used to clear template list.
     *
     * @var string[]
     */
    private $exclusions = [];

    /**
     * Resulting file pattern for templates.
     *
     * @var string
     */
    private $name = '{{ directory ? directory ~ "/" : "" }}{{ basename }}';

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     * @return $this
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getExclusions()
    {
        return $this->exclusions;
    }

    /**
     * @param string[] $exclusions
     * @return $this
     */
    public function setExclusions($exclusions)
    {
        $this->exclusions = $exclusions;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
