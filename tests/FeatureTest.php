<?php

namespace Dose\Feature\tests;

use Dose\Feature\ConnectorInterface;
use Dose\Feature\Entity;
use Dose\Feature\Feature;

class FeatureTest extends \PHPUnit_Framework_TestCase
{
    protected $context = 'context';
    protected $variant = 'variant';

    /** @test */
    public function it_should_return_a_variant()
    {
        $feature = $this->getFeature();

        $this->assertTrue($feature->getVariant('feature') == 'variant');
    }

    /** @test */
    public function it_should_cache_a_variant()
    {
        $feature = $this->getFeature();

        $feature->getVariant('feature');

        $this->variant = 'newVariant';

        $this->assertTrue($feature->getVariant('feature') == 'variant');
    }

    /** @test */
    public function it_should_test_for_same_feature_in_different_contexts_separately()
    {
        $feature = $this->getFeature();

        $this->assertTrue($feature->getVariant('feature') == 'variant');

        $this->context = 'newContext';
        $this->variant = 'newVariant';

        $this->assertTrue($feature->getVariant('feature') == 'newVariant');

        $this->context = 'context';

        $this->assertTrue($feature->getVariant('feature') == 'variant');
    }

    protected function getFeature()
    {
        return new Feature($this->getConnectorMock());
    }

    protected function getConnectorMock()
    {
        $mock = $this->getMockBuilder(ConnectorInterface::class)->getMock();
        $mock->method('getEntity')->willReturn($this->getEntityMock());
        $mock->method('getContext')->will($this->returnCallback(function () {
            return $this->context;
        }));

        return $mock;
    }

    protected function getEntityMock()
    {
        $mock = $this->getMockBuilder(Entity::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('getVariant')->will($this->returnCallback(function () {
            return $this->variant;
        }));

        return $mock;
    }
}
