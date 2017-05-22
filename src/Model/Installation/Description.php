<?php

namespace AmaTeam\Vagranted\Model\Installation;

/**
 * A simple data container for human-readable installer definitions.
 *
 * @author Etki <etki@etki.me>
 */
class Description
{
    /**
     * Single line description.
     *
     * @var string
     */
    private $name;

    /**
     * More verbose description
     *
     * @var string
     */
    private $description;

    /**
     * List of example patterns to show end user how he/she should use
     * installer.
     *
     * @var string[]
     */
    private $patterns = [];

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

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getPatterns()
    {
        return $this->patterns;
    }

    /**
     * @param string[] $patterns
     * @return $this
     */
    public function setPatterns($patterns)
    {
        $this->patterns = $patterns;
        return $this;
    }
}
