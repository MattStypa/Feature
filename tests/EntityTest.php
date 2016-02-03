<?php

namespace Dose\Feature\tests;

use Dose\Feature\Entity;
use Dose\Feature\Roller;

class EntityTest extends \PHPUnit_Framework_TestCase
{
    protected $roll = 50;

    /** @test */
    public function it_should_return_null_when_specified_variant_is_not_found()
    {
        $entity = $this->getEntity();
        $this->assertTrue($entity->getVariant(0) === null);
    }

    /** @test */
    public function it_should_return_null_when_neither_variant_won()
    {
        $entity = $this->getEntity();
        $entity->addVariant('variant1', 5);
        $entity->addVariant('variant2', 10);
        $entity->addVariant('variant3', 20);
        $this->assertTrue($entity->getVariant(0) === null);
    }

    /** @test */
    public function it_should_return_a_winning_variant()
    {
        $entity = $this->getEntity();
        $entity->addVariant('variant1', 30);
        $entity->addVariant('variant2', 30);
        $entity->addVariant('variant3', 30);
        $this->assertTrue($entity->getVariant(0) === 'variant2');
    }

    /** @test */
    public function it_should_return_numerical_variants_as_strings()
    {
        $entity = $this->getEntity();
        $entity->addVariant('0', 30);
        $entity->addVariant('1', 30);
        $entity->addVariant('2', 30);
        $this->assertTrue($entity->getVariant(0) === '1');
    }

    protected function getEntity()
    {
        return new Entity($this->getRollerMock());
    }

    protected function getRollerMock()
    {
        $mock = $this->getMockBuilder(Roller::class)->getMock();
        $mock->method('getRoll')->will($this->returnCallback(function () {
            return $this->roll;
        }));

        return $mock;
    }
}
