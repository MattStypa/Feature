<?php

namespace Dose\Feature;

/**
 * Represents a feature entity.
 *
 * @package Dose\Feature
 */
class Entity
{
    /**
     * @var Roller
     */
    protected $roller;

    /**
     * @var array Registered variants
     */
    protected $variants = [];

    /**
     * @var float Running total of variant odds
     */
    protected $oddsTotal = 0;

    /**
     * Entity constructor.
     *
     * @param Roller $roller
     */
    public function __construct(Roller $roller)
    {
        $this->roller = $roller;
    }

    /**
     * Adds variant.
     *
     * @param $name string
     * @param $odds float
     */
    public function addVariant($name, $odds)
    {
        $this->oddsTotal += $this->getSanitizedOdds($odds);
        $this->variants[(string)$name] = $this->oddsTotal;
    }

    /**
     * Gets odds after ensuring that the value is positive.
     *
     * @param $odds float
     * @return float
     */
    protected function getSanitizedOdds($odds)
    {
        $odds = (float)$odds;

        return $odds < 0 ? 0 : $odds;
    }

    /**
     * Gets a winning variant.
     *
     * @param $salt string
     * @return mixed
     */
    public function getVariant($salt)
    {
        // No chance of winning
        if ($this->oddsTotal == 0) {
            return null;
        }

        // First variant is a sure winner
        if (reset($this->variants) >= 100) {
            return (string)key($this->variants);
        }

        // Time to roll
        $roll = $this->roller->getRoll($salt);
        foreach ($this->variants as $variant => $odds) {
            if ($roll < $odds) {
                return (string)$variant;
            }
        }

        // Loser
        return null;
    }
}
