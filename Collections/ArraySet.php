<?php
/**
 * @author Martin Štekl <martin.stekl@gmail.com>
 * @copyright Copyright 2013, Martin Štekl
 * @license MIT
 * @version 0.5.0
 */

namespace stekycz\collections;

use ArrayIterator;

/**
 * Represents collection of values.
 */
class ArraySet implements ICollection {

	/**
	 * Array of all values in collection.
	 *
	 * @var array
	 */
	private $items = array();

	/**
	 * Creates new collection.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 */
	public function __construct($items = array()) {
		$this->clear();
		$this->addAll($items);
	}

	/**
	 * Adds given item into collection.
	 *
	 * @param mixed $item
	 * @return \stekycz\collections\ArraySet
	 */
	public function add($item) {
		if (!$this->contains($item)) {
			$this->items[] = $item;
		}
		return $this;
	}

	/**
	 * Adds all given items into collection.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 * @return \stekycz\collections\ArraySet
	 */
	public function addAll($items) {
		Collections::checkValidType($items);
		$items = Collections::isArrayType($items) ? $items : $items->items;
		$this->items = array_unique(array_merge($this->items, $items));
		return $this;
	}

	/**
	 * Cleans collection.
	 *
	 * @return \stekycz\collections\ArraySet
	 */
	public function clear() {
		$this->items = array();
		return $this;
	}

	/**
	 * Checks if given item is in collection.
	 *
	 * @param mixed $item
	 * @return bool
	 */
	public function contains($item) {
		return in_array($item, $this->items);
	}

	/**
	 * Checks if all given items are in collection.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 * @return bool
	 */
	public function containsAll($items) {
		$items = Collections::toSet($items);
		$difference = array_diff($items->items, $this->items);
		return empty($difference);
	}

	/**
	 * Checks if given collection is equal to current collection.
	 *
	 * @param array|\stekycz\collections\ArraySet $collection
	 * @return bool
	 */
	public function equals($collection) {
		try {
			$collection = Collections::toSet($collection);
		} catch (InvalidArgumentException $e) {
			return false;
		}
		return $this->containsAll($collection) && $collection->containsAll($this);
	}

	/**
	 * Checks if collection is empty.
	 *
	 * @return bool
	 */
	public function isEmpty() {
		return empty($this->items);
	}

	/**
	 * Removes given item from collection.
	 *
	 * @param mixed $item
	 * @return \stekycz\collections\ArraySet
	 */
	public function remove($item) {
		$index = array_search($item, $this->items);
		if ($index !== false) {
			unset($this->items[$index]);
		}
		return $this;
	}

	/**
	 * Removes all given items from collection.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 * @return \stekycz\collections\ArraySet
	 */
	public function removeAll($items) {
		$items = Collections::toSet($items);
		$this->items = array_diff($this->items, $items->items);
		return $this;
	}

	/**
	 * Retains all items in the collection which are in given collection.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 * @return \stekycz\collections\ArraySet
	 */
	public function retainAll($items) {
		Collections::checkValidType($items);
		return $this->removeAll($this->exclusiveOr($items));
	}

	/**
	 * Returns items in collection as array.
	 *
	 * @return array
	 */
	public function toArray() {
		$array = $this->items;
		return $array;
	}

	/**
	 * Returns count of items in collection.
	 *
	 * @return int
	 */
	public function count() {
		return count($this->items);
	}

	/**
	 * Returns iterator over items in collection.
	 *
	 * @return \Iterator
	 */
	public function getIterator() {
		return new ArrayIterator($this->items);
	}

	/**
	 * Should return the string representation of the object.
	 *
	 * @return string
	 */
	public function serialize() {
		return serialize($this->items);
	}

	/**
	 * Called during unserialization of the object.
	 *
	 * @param string $serialized
	 * @return void
	 */
	public function unserialize($serialized) {
		$this->items = unserialize($serialized);
	}

	// Own functions

	/**
	 * Returns intersection of given collection and current collection.
	 * Returned object is new.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 * @return \stekycz\collections\ArraySet
	 */
	public function intersect($items) {
		$items = Collections::toSet($items);
		return Collections::toSet(array_intersect($this->items, $items->items));
	}

	/**
	 * Returns result of XOR function on given collection and current collection.
	 * Returned object is new.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 * @return \stekycz\collections\ArraySet
	 */
	public function exclusiveOr($items) {
		$items = Collections::toSet($items);
		return Collections::toSet(array_merge(array_diff($this->items, $items->items), array_diff($items->items, $this->items)));
	}

}
