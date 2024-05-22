<?php

namespace Panopto\RemoteRecorderManagement;

class ArrayOfDayOfWeek implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var DayOfWeek[] $DayOfWeek
     */
    protected $DayOfWeek = null;


    public function __construct()
    {

    }

    /**
     * @return DayOfWeek[]
     */
    public function getDayOfWeek()
    {
      return $this->DayOfWeek;
    }

    /**
     * @param DayOfWeek[] $DayOfWeek
     * @return \Panopto\RemoteRecorderManagement\ArrayOfDayOfWeek
     */
    public function setDayOfWeek(array $DayOfWeek = null)
    {
      $this->DayOfWeek = $DayOfWeek;
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
      return isset($this->DayOfWeek[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to retrieve
     * @return DayOfWeek
     */
    public function offsetGet(mixed $offset): mixed
    {
      return $this->DayOfWeek[$offset];
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to assign the value to
     * @param DayOfWeek $value The value to set
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
      if (!isset($offset)) {
        $this->DayOfWeek[] = $value;
      } else {
        $this->DayOfWeek[$offset] = $value;
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
      unset($this->DayOfWeek[$offset]);
    }

    /**
     * Iterator implementation
     *
     * @return DayOfWeek Return the current element
     */
    public function current(): mixed
    {
      return current($this->DayOfWeek);
    }

    /**
     * Iterator implementation
     * Move forward to next element
     *
     * @return void
     */
    public function next(): void
    {
      next($this->DayOfWeek);
    }

    /**
     * Iterator implementation
     *
     * @return string|null Return the key of the current element or null
     */
    public function key(): mixed
    {
      return key($this->DayOfWeek);
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
      reset($this->DayOfWeek);
    }

    /**
     * Countable implementation
     *
     * @return DayOfWeek Return count of elements
     */
    public function count(): int
    {
      return count($this->DayOfWeek);
    }

}
