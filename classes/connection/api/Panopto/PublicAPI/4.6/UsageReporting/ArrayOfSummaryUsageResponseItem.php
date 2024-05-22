<?php

namespace Panopto\UsageReporting;

class ArrayOfSummaryUsageResponseItem implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var SummaryUsageResponseItem[] $SummaryUsageResponseItem
     */
    protected $SummaryUsageResponseItem = null;


    public function __construct()
    {

    }

    /**
     * @return SummaryUsageResponseItem[]
     */
    public function getSummaryUsageResponseItem()
    {
      return $this->SummaryUsageResponseItem;
    }

    /**
     * @param SummaryUsageResponseItem[] $SummaryUsageResponseItem
     * @return \Panopto\UsageReporting\ArrayOfSummaryUsageResponseItem
     */
    public function setSummaryUsageResponseItem(array $SummaryUsageResponseItem = null)
    {
      $this->SummaryUsageResponseItem = $SummaryUsageResponseItem;
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
      return isset($this->SummaryUsageResponseItem[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to retrieve
     * @return SummaryUsageResponseItem
     */
    public function offsetGet(mixed $offset): mixed
    {
      return $this->SummaryUsageResponseItem[$offset];
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to assign the value to
     * @param SummaryUsageResponseItem $value The value to set
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
      if (!isset($offset)) {
        $this->SummaryUsageResponseItem[] = $value;
      } else {
        $this->SummaryUsageResponseItem[$offset] = $value;
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
      unset($this->SummaryUsageResponseItem[$offset]);
    }

    /**
     * Iterator implementation
     *
     * @return SummaryUsageResponseItem Return the current element
     */
    public function current(): mixed
    {
      return current($this->SummaryUsageResponseItem);
    }

    /**
     * Iterator implementation
     * Move forward to next element
     *
     * @return void
     */
    public function next(): void
    {
      next($this->SummaryUsageResponseItem);
    }

    /**
     * Iterator implementation
     *
     * @return string|null Return the key of the current element or null
     */
    public function key(): mixed
    {
      return key($this->SummaryUsageResponseItem);
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
      reset($this->SummaryUsageResponseItem);
    }

    /**
     * Countable implementation
     *
     * @return SummaryUsageResponseItem Return count of elements
     */
    public function count(): int
    {
      return count($this->SummaryUsageResponseItem);
    }

}
