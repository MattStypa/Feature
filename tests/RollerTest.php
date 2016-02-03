<?php

namespace Dose\Feature\tests;

use Dose\Feature\Roller;

class RollerTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_should_always_roll_the_same_for_the_same_salt()
    {
        $salt = mt_rand();
        $roller = $this->getRoller();
        $control = $roller->getRoll($salt);

        for ($i = 0; $i < 1000; $i++) {
            $this->assertTrue($roller->getRoll($salt) == $control);
        }
    }

    /** @test */
    public function it_roll_results_should_be_in_half_open_set()
    {
        $roller = $this->getRoller();

        for ($i = 0; $i < 1000; $i++) {
            $salt = mt_rand();
            $roll = $roller->getRoll($salt);
            $this->assertTrue($roll >= 0);
            $this->assertTrue($roll < 100);
        }
    }

    /** @test */
    public function it_roll_results_should_tend_toward_uniform_distribution()
    {
        $roller = $this->getRoller();
        $hits = array_fill(0, 100, 0);
        $uniform = false;

        for ($i = 0; $i < 1000; $i++) {
            for ($j = 0; $j < 1000; $j++) {
                $salt = mt_rand();
                $roll = $roller->getRoll($salt);
                $hits[floor($roll)]++;
            }

            $min = min($hits);
            $max = max($hits);
            $diff = ($max - $min) / $min * 100;
            if ($diff < 50) {
                $uniform = true;
                break;
            }
        }

        $this->assertTrue($uniform);
    }

    protected function getRoller()
    {
        return new Roller();
    }
}
