<?php

namespace Panopto\SessionManagement;

class ArrayOfSession implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var Session[] $Session
     */
    protected $Session = null;


    public function __construct()
    {

    }

    /**
     * @return Session[]
     */
    public function getSession()
    {
      return $this->Session;
    }

    /**
     * @param Session[] $Session
     * @return \Panopto\SessionManagement\ArrayOfSession
     */
    public function setSession(array $Session = null)
    {
      $this->Session = $Session;
      return $this;
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset An offset to check for
     * @return boolean true on success or false on failure
     */
    public function offsetExists(mixed $offset): bool
    {
      return isset($this->Session[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to retrieve
     * @return Session
     */
    public function offsetGet(mixed $offset): mixed
    {
      return $this->Session[$offset];
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to assign the value to
     * @param Session $value The value to set
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
      if (!isset($offset)) {
        $this->Session[] = $value;
      } else {
        $this->Session[$offset] = $value;
      }
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to unset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
      unset($this->Session[$offset]);
    }

    /**
     * Iterator implementation
     *
     * @return Session Return the current element
     */
    public function current(): mixed
    {
      return current($this->Session);
    }

    /**
     * Iterator implementation
     * Move forward to next element
     *
     * @return void
     */
    public function next(): void
    {
      next($this->Session);
    }

    /**
     * Iterator implementation
     *
     * @return string|null Return the key of the current element or null
     */
    public function key(): mixed
    {
      return key($this->Session);
    }

    /**
     * Iterator implementation
     *
     * @return boolean Return the validity of the current position
     */
    public function valid(): bool
    {
      return $this->key() !== null;
    }

    /**
     * Iterator implementation
     * Rewind the Iterator to the first element
     *
     * @return void
     */
    public function rewind(): void
    {
      reset($this->Session);
    }

    /**
     * Countable implementation
     *
     * @return Session Return count of elements
     */
    public function count(): int
    {
      return count($this->Session);
    }

}
