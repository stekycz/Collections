<?php
/**
 * @author Martin Štekl <martin.stekl@gmail.com>
 * @copyright Copyright 2013, Martin Štekl
 * @license MIT
 * @version 0.1.0
 */

namespace stekycz\collections;

use Countable;
use IteratorIterator;
use LogicException;
use Serializable;
use Traversable;
use IteratorAggregate;

/**
 * Represents set of values.
 */
class ArraySet implements Countable, IteratorAggregate, Traversable, Serializable {

	/**
	 * Array of all values in set.
	 *
	 * @var array
	 */
	private $items = array();

	/**
	 * Creates new set.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 */
	public function __construct($items = array()) {
		$this->clear();
		$this->addAll($items);
	}

	// Java inspiration functions

	/**
	 * Adds given item into set.
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
	 * Adds all given items into set.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 * @return \stekycz\collections\ArraySet
	 */
	public function addAll($items) {
		$items = Collections::toSet($items);
		foreach ($items as $item) {
			$this->add($item);
		}
		return $this;
	}

	/**
	 * Cleans set.
	 *
	 * @return \stekycz\collections\ArraySet
	 */
	public function clear() {
		$this->items = array();
		return $this;
	}

	/**
	 * Checks if given item is in set.
	 *
	 * @param mixed $item
	 * @return bool
	 */
	public function contains($item) {
		return in_array($item, $this->items);
	}

	/**
	 * Checks if all given items are in set.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 * @return bool
	 */
	public function containsAll($items) {
		$items = Collections::toSet($items);
		if ($this->count() < $items->count()) {
			return false;
		}
		foreach ($items as $item) {
			if (!$this->contains($item)) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Checks if given set is equal to current set.
	 *
	 * @param array|\stekycz\collections\ArraySet $set
	 * @return bool
	 */
	public function equals($set) {
		$set = Collections::toSet($set);
		return $this->containsAll($set) && $set->containsAll($this);
	}

	/**
	 * Checks if current set is empty.
	 *
	 * @return bool
	 */
	public function isEmpty() {
		return $this->size() <= 0;
	}

	/**
	 * Returns iterator over values in set.
	 *
	 * @deprecated
	 * @return \Iterator
	 */
	public function iterator() {
		return $this->getIterator();
	}

	/**
	 * Removes given item from set.
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
	 * Removes all given items from set.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 * @return \stekycz\collections\ArraySet
	 */
	public function removeAll($items) {
		$items = Collections::toSet($items);
		foreach ($items as $item) {
			$this->remove($item);
		}
		return $this;
	}

	/**
	 * Retains all items in the set which are in given set.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 * @return \stekycz\collections\ArraySet
	 */
	public function retainAll($items) {
		$items = Collections::toSet($items);
		return $this->removeAll($this->exclusiveOr($items));
	}

	/**
	 * Returns size of set (count of items).
	 *
	 * @deprecated
	 * @return int
	 */
	public function size() {
		return $this->count();
	}

	/**
	 * Returns items in set as array.
	 *
	 * @return array
	 */
	public function toArray() {
		$array = $this->items;
		return $array;
	}

	// PHP functions

	/**
	 * Returns size of set (count of items).
	 *
	 * @return int
	 */
	public function count() {
		return count($this->items);
	}

	/**
	 * Returns iterator over values in set.
	 *
	 * @return \Iterator
	 */
	public function getIterator() {
		return new IteratorIterator($this->items);
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
	 * Returns intersection of given set and current set.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 * @return \stekycz\collections\ArraySet
	 */
	public function intersect($items) {
		$items = Collections::toSet($items);
		return Collections::toSet(array_intersect($this->items, $items->items));
	}

	/**
	 * Returns result of XOR function on given set and current set.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 * @return \stekycz\collections\ArraySet
	 */
	public function exclusiveOr($items) {
		$items = Collections::toSet($items);
		return Collections::toSet(array_merge(array_diff($this->items, $items->items), array_diff($items->items, $this->items)));
	}

}
