<?php

namespace Dose\Feature;

/**
 * Retrieves and caches individual feature entities and selected variants.
 *
 * @package Dose\Feature
 */
class Feature
{
    /**
     * @var ConnectorInterface Accessor to external configuration
     */
    protected $connector;

    /**
     * @var array Cache array for selected variants
     */
    protected $winningVariants = [];

    /**
     * Feature constructor.
     *
     * @param ConnectorInterface $connector
     */
    public function __construct(ConnectorInterface $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Gets winning variant for the specified feature.
     *
     * @param $name string
     * @return mixed
     */
    public function getVariant($name)
    {
        $featureId = $this->connector->getContext() . ':' . $name;

        if (!isset($this->winningVariants[$featureId])) {
            $this->winningVariants[$featureId] = $this->getVariantFromEntity($name, $featureId);
        }

        return $this->winningVariants[$featureId];
    }

    /**
     * Gets a winning variant from feature entity.
     *
     * @param $name string
     * @param $salt string
     * @return mixed
     */
    protected function getVariantFromEntity($name, $salt)
    {
        $feature = $this->connector->getEntity($name);

        return $feature->getVariant($salt);
    }
}
