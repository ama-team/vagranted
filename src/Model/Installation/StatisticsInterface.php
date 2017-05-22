<?php

namespace AmaTeam\Vagranted\Model\Installation;

use DateTimeInterface;

/**
 * Resource set usage statistics.
 *
 * @author Etki <etki@etki.me>
 */
interface StatisticsInterface
{
    /**
     * @param DateTimeInterface|null $usedAt
     * @return $this
     */
    public function used(DateTimeInterface $usedAt = null);

    /**
     * @param DateTimeInterface|null $installedAt
     * @return $this
     */
    public function installed(DateTimeInterface $installedAt = null);

    /**
     * @return int
     */
    public function getUsageCounter();

    /**
     * @return DateTimeInterface
     */
    public function getInstalledAt();

    /**
     * @return DateTimeInterface
     */
    public function getUsedAt();
}
