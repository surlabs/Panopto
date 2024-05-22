<?php

namespace Panopto\SessionManagement;

class ArrayOfFolderAvailabilitySettings implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var FolderAvailabilitySettings[] $FolderAvailabilitySettings
     */
    protected $FolderAvailabilitySettings = null;


    public function __construct()
    {

    }

    /**
     * @return FolderAvailabilitySettings[]
     */
    public function getFolderAvailabilitySettings()
    {
      return $this->FolderAvailabilitySettings;
    }

    /**
     * @param FolderAvailabilitySettings[] $FolderAvailabilitySettings
     * @return \Panopto\SessionManagement\ArrayOfFolderAvailabilitySettings
     */
    public function setFolderAvailabilitySettings(array $FolderAvailabilitySettings = null)
    {
      $this->FolderAvailabilitySettings = $FolderAvailabilitySettings;
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
      return isset($this->FolderAvailabilitySettings[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to retrieve
     * @return FolderAvailabilitySettings
     */
    public function offsetGet(mixed $offset): mixed
    {
      return $this->FolderAvailabilitySettings[$offset];
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to assign the value to
     * @param FolderAvailabilitySettings $value The value to set
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
      if (!isset($offset)) {
        $this->FolderAvailabilitySettings[] = $value;
      } else {
        $this->FolderAvailabilitySettings[$offset] = $value;
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
      unset($this->FolderAvailabilitySettings[$offset]);
    }

    /**
     * Iterator implementation
     *
     * @return FolderAvailabilitySettings Return the current element
     */
    public function current(): mixed
    {
      return current($this->FolderAvailabilitySettings);
    }

    /**
     * Iterator implementation
     * Move forward to next element
     *
     * @return void
     */
    public function next(): void
    {
      next($this->FolderAvailabilitySettings);
    }

    /**
     * Iterator implementation
     *
     * @return string|null Return the key of the current element or null
     */
    public function key(): mixed
    {
      return key($this->FolderAvailabilitySettings);
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
      reset($this->FolderAvailabilitySettings);
    }

    /**
     * Countable implementation
     *
     * @return FolderAvailabilitySettings Return count of elements
     */
    public function count(): int
    {
      return count($this->FolderAvailabilitySettings);
    }

}
