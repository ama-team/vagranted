<?php

namespace AmaTeam\Vagranted\Model\Configuration;

use AmaTeam\Vagranted\Application\Configuration\Defaults;

/**
 * @author Etki <etki@etki.me>
 */
class LoggerConfiguration
{
    /**
     * @var string
     */
    private $level = Defaults::LOGGER_LEVEL;
    /**
     * @var string
     */
    private $target = Defaults::LOGGER_TARGET;
    /**
     * @var string
     */
    private $format = Defaults::LOGGER_FORMAT;
    /**
     * @var string
     */
    private $prefix = Defaults::LOGGER_PREFIX;

    /**
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param string $level
     * @return $this
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $target
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }
}
