<?php

namespace ntentan\atiaa;

/**
 * Default implementation of DescriptorFactoryInterface.
 */
class DefaultDescriptorFactory implements DescriptorFactoryInterface
{
    public function createDescriptor(Driver $driver): DescriptorInterface
    {
        $config = $driver->getConfig();
        $descriptorClass = __NAMESPACE__.'\\descriptors\\'.ucfirst($config['driver']).'Descriptor';
        return new $descriptorClass($driver);
    }
}
