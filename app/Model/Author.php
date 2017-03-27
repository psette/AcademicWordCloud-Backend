<?php

/**
 * A musical Author.
 */
class Author
{
    /**
     * The name of the Author.
     *
     * @var string
     */
    var $name;

    /**
     * The unique identifier representing the Author.
     *
     * @var string
     */
    var $identifier;

    /**
     * A URL pointing to an image representing the Author.
     *
     * @var string
     */
    var $imageURL;

    /**
     * The Papers composed by the Author.
     *
     * @var ModelSet
     */
    var $papers;

    /**
     * The Author's most frequently used Words, sorted by frequency.
     *
     * @var array
     */
    var $frequentWords;

    /**
     * The Author constructor.
     */
    function __construct() 
    {
        $this->papers = new ModelSet();
        $this->frequentWords = [];
    }
}

?>
