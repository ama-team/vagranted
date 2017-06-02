<?php

namespace AmaTeam\Vagranted\Model;

/**
 * Simple wrapper that allows to hide and change particular configuration
 * instance.
 *
 * @author Etki <etki@etki.me>
 */
class ConfigurationWrapper implements ConfigurationInterface
{
    /**
     * @var ConfigurationInterface
     */
    private $enclosure;

    /**
     * @param ConfigurationInterface $enclosure
     */
    public function __construct(ConfigurationInterface $enclosure = null)
    {
        $this->enclosure = $enclosure ?: new Configuration();
    }

    /**
     * @return ConfigurationInterface
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * @param ConfigurationInterface $enclosure
     * @return $this
     */
    public function setEnclosure(ConfigurationInterface $enclosure)
    {
        $this->enclosure = $enclosure;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getWorkingDirectory()
    {
        return $this->enclosure->getWorkingDirectory();
    }

    /**
     * @inheritdoc
     */
    public function getProjectDirectory()
    {
        return $this->enclosure->getProjectDirectory();
    }

    /**
     * @inheritdoc
     */
    public function getTargetDirectory()
    {
        return $this->enclosure->getTargetDirectory();
    }

    /**
     * @inheritdoc
     */
    public function getDataDirectory()
    {
        return $this->enclosure->getDataDirectory();
    }

    /**
     * @inheritdoc
     */
    public function getExtras()
    {
        return $this->enclosure->getExtras();
    }

    /**
     * @inheritdoc
     */
    public function getLogger()
    {
        return $this->enclosure->getLogger();
    }
}
