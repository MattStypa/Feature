<?php

namespace Dose\Feature;

/**
 * Provides externally available configuration points.
 *
 * @package Dose\Feature
 */
interface ConnectorInterface
{
    /**
     * Gets a defined feature entity object.
     *
     * @param $name string
     * @return Entity
     */
    public function getEntity($name);

    /**
     * Gets environment context.
     *
     * @return string
     */
    public function getContext();
}
