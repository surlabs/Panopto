<?php

namespace Panopto\AccessManagement;

class ArrayOfguid implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var guid[] $guid
     */
    protected $guid = null;


    public function __construct()
    {

    }

    /**
     * @return guid[]
     */
    public function getGuid()
    {
      return $this->guid;
    }

    /**
     * @param guid[] $guid
     * @return \Panopto\AccessManagement\ArrayOfguid
     */
    public function setGuid(array $guid = null)
    {
      $this->guid = $guid;
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
      return isset($this->guid[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to retrieve
     * @return guid
     */
    public function offsetGet(mixed $offset): mixed
    {
      return $this->guid[$offset];
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to assign the value to
     * @param guid $value The value to set
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
      if (!isset($offset)) {
        $this->guid[] = $value;
      } else {
        $this->guid[$offset] = $value;
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
      unset($this->guid[$offset]);
    }

    /**
     * Iterator implementation
     *
     * @return guid Return the current element
     */
    public function current(): mixed
    {
      return current($this->guid);
    }

    /**
     * Iterator implementation
     * Move forward to next element
     *
     * @return void
     */
    public function next(): void
    {
      next($this->guid);
    }

    /**
     * Iterator implementation
     *
     * @return string|null Return the key of the current element or null
     */
    public function key(): mixed
    {
      return key($this->guid);
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
      reset($this->guid);
    }

    /**
     * Countable implementation
     *
     * @return guid Return count of elements
     */
    public function count(): int
    {
      return count($this->guid);
    }

}
