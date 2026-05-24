<?php

namespace ntentan\atiaa;

/**
 * Interface representing a database schema descriptor.
 */
interface DescriptorInterface
{
    /**
     * Returns the description of the database as an array.
     *
     * @return array
     */
    public function describe();

    /**
     * Returns the descriptions of the specified tables in a schema.
     *
     * @param string $schema
     * @param array $requestedTables
     * @param bool $includeViews
     * @return array
     */
    public function describeTables($schema, $requestedTables = [], $includeViews = false);

    /**
     * Set clean defaults flag.
     *
     * @param bool $cleanDefaults
     */
    public function setCleanDefaults($cleanDefaults);
}
