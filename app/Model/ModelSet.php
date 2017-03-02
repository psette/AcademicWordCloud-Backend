<?php

/**
 * SplObjectStorage subclass for storing model objects. Uniqueness is determined by object's 'identifier' property.
 */
class ModelSet extends SplObjectStorage
{
    /**
     * Determines the hash for a given object.
     *
     * @param artist $object.
     *
     * @return string.
     */
    public function getHash($object)
    {
        return $object->identifier;
    }
}

?>