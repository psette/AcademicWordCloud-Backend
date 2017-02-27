<?php

/**
 * SplObjectStorage subclass for storing model objects. Uniqueness is determined by object's 'identifier' property.
 */
class ModelSet extends SplObjectStorage
{
    public function getHash($object)
    {
        return $object->identifier;
    }
}

?>