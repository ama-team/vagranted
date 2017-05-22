<?php

namespace AmaTeam\Vagranted\Language;

use Iterator;

/**
 * @author Etki <etki@etki.me>
 */
class MappingIterator implements Iterator
{
    /**
     * @var Iterator
     */
    private $iterator;

    /**
     * @var callable
     */
    private $mapper;

    /**
     * @param Iterator $iterator
     * @param callable $mapper
     */
    public function __construct(Iterator $iterator, callable $mapper)
    {
        $this->iterator = $iterator;
        $this->mapper = $mapper;
    }

    public function current()
    {
        return call_user_func($this->mapper, $this->iterator->current());
    }

    public function next()
    {
        $this->iterator->next();
    }

    public function key()
    {
        return $this->iterator->key();
    }

    public function valid()
    {
        return $this->iterator->valid();
    }

    public function rewind()
    {
        $this->iterator->rewind();
    }
}
