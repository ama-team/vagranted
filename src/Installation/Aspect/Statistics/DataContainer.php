<?php

namespace AmaTeam\Vagranted\Installation\Aspect\Statistics;

use DateTimeInterface;

/**
 * @author Etki <etki@etki.me>
 */
class DataContainer
{
    /**
     * @var int
     */
    private $usages = 0;
    /**
     * @var DateTimeInterface
     */
    private $usedAt;
    /**
     * @var DateTimeInterface
     */
    private $installedAt;

    /**
     * @return int
     */
    public function getUsages()
    {
        return $this->usages;
    }

    /**
     * @param int $usages
     * @return $this
     */
    public function setUsages($usages)
    {
        $this->usages = $usages;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUsedAt()
    {
        return $this->usedAt;
    }

    /**
     * @param DateTimeInterface $usedAt
     * @return $this
     */
    public function setUsedAt($usedAt)
    {
        $this->usedAt = $usedAt;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getInstalledAt()
    {
        return $this->installedAt;
    }

    /**
     * @param DateTimeInterface $installedAt
     * @return $this
     */
    public function setInstalledAt($installedAt)
    {
        $this->installedAt = $installedAt;
        return $this;
    }
}
