<?php

namespace Dose\Feature;

/**
 * Provides a pseudo RNG.
 *
 * @package Dose\Feature
 */
class Roller
{
    /**
     * Gets a pseudo random number. This will always be the same for the same salt.
     *
     * @param $salt
     * @return float
     */
    public function getRoll($salt)
    {
        // Create a hash for the salt
        $hex = hash('sha256', $salt);
        // Maximum bit length used. Leave out one bit for a sign and one bit for
        // open range
        $bits = min(PHP_INT_SIZE * 8 - 2, strlen($hex));
        $value = 0;
        for ($i = 0; $i < $bits; $i++) {
            // Project each digit of the hash into a binary value
            $value = ($value << 1) + (hexdec($hex[$i]) < 8 ? 0 : 1);
        }

        // Percent of binary value of the possible maximum
        return $value / (1 << $bits) * 100;
    }
}
