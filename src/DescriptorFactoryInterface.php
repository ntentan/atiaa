<?php

namespace ntentan\atiaa;

/**
 * Interface for factory classes that create Descriptor instances.
 */
interface DescriptorFactoryInterface
{
    /**
     * Creates a descriptor instance for the given driver.
     *
     * @param Driver $driver
     * @return DescriptorInterface
     */
    public function createDescriptor(Driver $driver): DescriptorInterface;
}
