<?php

namespace Panopto\SessionManagement;

class ArrayOfFolder implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var Folder[] $Folder
     */
    protected $Folder = null;


    public function __construct()
    {

    }

    /**
     * @return Folder[]
     */
    public function getFolder()
    {
      return $this->Folder;
    }

    /**
     * @param Folder[] $Folder
     * @return \Panopto\SessionManagement\ArrayOfFolder
     */
    public function setFolder(array $Folder = null)
    {
      $this->Folder = $Folder;
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
      return isset($this->Folder[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to retrieve
     * @return Folder
     */
    public function offsetGet(mixed $offset): mixed
    {
      return $this->Folder[$offset];
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to assign the value to
     * @param Folder $value The value to set
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
      if (!isset($offset)) {
        $this->Folder[] = $value;
      } else {
        $this->Folder[$offset] = $value;
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
      unset($this->Folder[$offset]);
    }

    /**
     * Iterator implementation
     *
     * @return Folder Return the current element
     */
    public function current(): mixed
    {
      return current($this->Folder);
    }

    /**
     * Iterator implementation
     * Move forward to next element
     *
     * @return void
     */
    public function next(): void
    {
      next($this->Folder);
    }

    /**
     * Iterator implementation
     *
     * @return string|null Return the key of the current element or null
     */
    public function key(): mixed
    {
      return key($this->Folder);
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
      reset($this->Folder);
    }

    /**
     * Countable implementation
     *
     * @return Folder Return count of elements
     */
    public function count(): int
    {
      return count($this->Folder);
    }

}
