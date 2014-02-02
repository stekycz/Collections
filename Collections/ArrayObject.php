<?php

namespace stekycz\collections;



class ArrayObject implements ICollection, \ArrayAccess
{

	/**
	 * @var array
	 */
	private $items = array();



	/**
	 * @param array|\stekycz\collections\ICollection $items
	 */
	public function __construct($items = array())
	{
		$this->clear();
		$this->addAll($items);
	}



	/**
	 * @param mixed $item
	 * @return \stekycz\collections\ArrayObject
	 */
	public function add($item)
	{
		$this->items[] = $item;

		return $this;
	}



	/**
	 * @param array|\stekycz\collections\ICollection $items
	 * @return \stekycz\collections\ArrayObject
	 */
	public function addAll($items)
	{
		Collections::checkValidType($items);
		$items = Collections::isArrayType($items) ? $items : $items->toArray();
		$this->items = array_merge($this->items, $items);

		return $this;
	}



	/**
	 * @return \stekycz\collections\ArraySet
	 */
	public function clear()
	{
		$this->items = array();

		return $this;
	}



	/**
	 * @param mixed $item
	 * @return bool
	 */
	public function contains($item)
	{
		return in_array($item, $this->items);
	}



	/**
	 * @param array|\stekycz\collections\ICollection $items
	 * @return bool
	 */
	public function containsAll($items)
	{
		$items = Collections::toArrayObject($items);
		$difference = array_diff($items->items, $this->items);

		return empty($difference);
	}



	/**
	 * Checks if given collection is equal to current collection.
	 *
	 * @param array|\stekycz\collections\ICollection $collection
	 * @return bool
	 */
	public function equals($collection)
	{
		try {
			$collection = Collections::toArrayObject($collection);
		} catch (InvalidArgumentException $e) {
			return FALSE;
		}

		return $this->count() == $collection->count() && $this->containsAll($collection) && $collection->containsAll($this);
	}



	/**
	 * @return bool
	 */
	public function isEmpty()
	{
		return empty($this->items);
	}



	/**
	 * @param mixed $item
	 * @return \stekycz\collections\ArrayObject
	 */
	public function remove($item)
	{
		while (($index = array_search($item, $this->items)) !== FALSE) {
			unset($this->items[$index]);
		}

		return $this;
	}



	/**
	 * Removes all given items from collection.
	 *
	 * @param array|\stekycz\collections\ArrayObject $items
	 * @return \stekycz\collections\ArrayObject
	 */
	public function removeAll($items)
	{
		$items = Collections::toArrayObject($items);
		$this->items = array_diff($this->items, $items->items);

		return $this;
	}



	/**
	 * @return array
	 */
	public function toArray()
	{
		$array = array_slice($this->items, 0, NULL, TRUE);

		return $array;
	}



	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->items);
	}



	/**
	 * @return \Iterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->items);
	}



	/**
	 * @return string
	 */
	public function serialize()
	{
		return serialize($this->items);
	}



	/**
	 * @param string $serialized
	 * @return void
	 */
	public function unserialize($serialized)
	{
		$this->clear();
		$this->items = unserialize($serialized);
	}



	/**
	 * @param callable $callback
	 * @return \stekycz\collections\ArrayObject
	 */
	public function filter($callback)
	{
		return new self(array_filter($this->items, $callback));
	}



	/**
	 * @param callable($item) $callback
	 * @return \stekycz\collections\ArrayObject
	 */
	public function map($callback)
	{
		return new self(array_map($callback, $this->items));
	}



	/**
	 * @param callable($result, $item) $callback
	 * @param mixed $initial
	 * @return mixed
	 */
	public function reduce($callback, $initial = NULL)
	{
		return array_reduce($this->items, $callback, $initial);
	}



	/**
	 * @return \stekycz\collections\ArrayObject
	 */
	public function unique()
	{
		return new self(array_unique($this->items));
	}



	/**
	 * @param callable($value, $key) $callback
	 * @return \stekycz\collections\ArrayObject
	 */
	public function each($callback)
	{
		reset($this->items);
		while (list($key, $value) = each($this->items)) {
			$callback($value, $key);
		}

		return $this;
	}



	/**
	 * @return \stekycz\collections\ArrayObject
	 */
	public function reverse()
	{
		return new self(array_reverse($this->items, TRUE));
	}



	/**
	 * @param callable($a, $b)|NULL $callback
	 * @param bool $reverse Used only if $callback is not specified
	 * @return \stekycz\collections\ArrayObject
	 */
	public function sort($callback = NULL, $reverse = FALSE)
	{
		$array = $this->toArray();
		if (($callback !== NULL && !uasort($array, $callback)) || ($reverse && arsort($array)) || asort($array)) {
			return new self($array);
		}

		throw new InvalidStateException("Array was not sorted because of unknown cause.");
	}



	/**
	 * @param callable ($a, $b)|NULL $callback
	 * @param bool $reverse Used only if $callback is not specified
	 * @return \stekycz\collections\ArrayObject
	 */
	public function sortByKeys($callback = NULL, $reverse = FALSE)
	{
		$array = $this->toArray();
		if (($callback !== NULL && !uksort($array, $callback)) || ($reverse && krsort($array)) || ksort($array)) {
			return new self($array);
		}

		throw new InvalidStateException("Array was not sorted because of unknown cause.");
	}



	/**
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->items);
	}



	/**
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		if (!isset($this[$offset])) {
			throw new KeyNotFoundException("Key '$offset' was not found in the array.");
		}

		return $this->items[$offset];
	}



	/**
	 * @param mixed $offset
	 * @param mixed $value
	 * @return \stekycz\collections\ArrayObject
	 */
	public function offsetSet($offset, $value)
	{
		$this->items[$offset] = $value;

		return $this;
	}



	/**
	 * @param mixed $offset
	 * @return \stekycz\collections\ArrayObject
	 */
	public function offsetUnset($offset)
	{
		unset($this->items[$offset]);

		return $this;
	}

}
