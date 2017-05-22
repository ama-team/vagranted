<?php

namespace AmaTeam\Vagranted\Model\ResourceSet\Configuration;

use AmaTeam\Vagranted\Model\Filesystem\ExclusiveFilePatternInterface;

/**
 * @author Etki <etki@etki.me>
 */
class AssetFilter implements ExclusiveFilePatternInterface
{
    /**
     * Glob pattern that is used to find matching files.
     *
     * @var string
     */
    private $pattern;

    /**
     * List of glob patterns used to exclude certain matches from resulting set.  
     *
     * @var string[]
     */
    private $exclusions = [];

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
    
    
}
