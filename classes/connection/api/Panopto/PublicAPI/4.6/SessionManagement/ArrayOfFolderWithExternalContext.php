<?php

namespace Panopto\SessionManagement;

class ArrayOfFolderWithExternalContext implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var FolderWithExternalContext[] $FolderWithExternalContext
     */
    protected $FolderWithExternalContext = null;


    public function __construct()
    {

    }

    /**
     * @return FolderWithExternalContext[]
     */
    public function getFolderWithExternalContext()
    {
      return $this->FolderWithExternalContext;
    }

    /**
     * @param FolderWithExternalContext[] $FolderWithExternalContext
     * @return \Panopto\SessionManagement\ArrayOfFolderWithExternalContext
     */
    public function setFolderWithExternalContext(array $FolderWithExternalContext = null)
    {
      $this->FolderWithExternalContext = $FolderWithExternalContext;
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
      return isset($this->FolderWithExternalContext[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to retrieve
     * @return FolderWithExternalContext
     */
    public function offsetGet(mixed $offset): mixed
    {
      return $this->FolderWithExternalContext[$offset];
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to assign the value to
     * @param FolderWithExternalContext $value The value to set
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
      if (!isset($offset)) {
        $this->FolderWithExternalContext[] = $value;
      } else {
        $this->FolderWithExternalContext[$offset] = $value;
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
      unset($this->FolderWithExternalContext[$offset]);
    }

    /**
     * Iterator implementation
     *
     * @return FolderWithExternalContext Return the current element
     */
    public function current(): mixed
    {
      return current($this->FolderWithExternalContext);
    }

    /**
     * Iterator implementation
     * Move forward to next element
     *
     * @return void
     */
    public function next(): void
    {
      next($this->FolderWithExternalContext);
    }

    /**
     * Iterator implementation
     *
     * @return string|null Return the key of the current element or null
     */
    public function key(): mixed
    {
      return key($this->FolderWithExternalContext);
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
      reset($this->FolderWithExternalContext);
    }

    /**
     * Countable implementation
     *
     * @return FolderWithExternalContext Return count of elements
     */
    public function count(): int
    {
      return count($this->FolderWithExternalContext);
    }

}
