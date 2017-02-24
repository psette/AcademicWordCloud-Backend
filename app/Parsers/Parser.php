<?php

/**
 * Required functions to serialize model objects to and from JSON.
 */
interface Parser
{
    /**
     * Parses the JSON and returns a model object.
     *
     * @param string $json The JSON representation of a model object.
     *
     * @return object Returns a model object populated with data from $json.
     */
    function parseObject($json);

    /**
     * Serializes a model object to JSON.
     *
     * @param object $object The model object to be serialized.
     *
     * @return string Returns the JSON representation of the model object.
     */
    function serializeObject($object);
}

?>