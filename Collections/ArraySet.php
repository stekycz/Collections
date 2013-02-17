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

class ArraySet implements Countable, IteratorAggregate, Traversable, Serializable {

	/**
	 * @var array
	 */
	private $items = array();

	/**
	 * @param array|ArraySet $items
	 */
	public function __construct($items = array()) {
		$this->clear();
		$this->addAll($items);
	}

	// Java inspiration functions

	/**
	 * @param mixed $item
	 * @return ArraySet
	 */
	public function add($item) {
		if (!$this->contains($item)) {
			$this->items[] = $item;
		}
		return $this;
	}

	/**
	 * @param array|ArraySet $items
	 * @return ArraySet
	 */
	public function addAll($items) {
		$items = Collections::toSet($items);
		foreach ($items as $item) {
			$this->add($item);
		}
		return $this;
	}

	/**
	 * @return ArraySet
	 */
	public function clear() {
		$this->items = array();
		return $this;
	}

	/**
	 * @param mixed $item
	 * @return bool
	 */
	public function contains($item) {
		return in_array($item, $this->items);
	}

	/**
	 * @param array|ArraySet $items
	 * @return bool
	 */
	public function containsAll($items) {
		$items = Collections::toSet($items);
		if ($this->count() != $items->count()) {
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
	 * @param array|ArraySet $set
	 * @return bool
	 */
	public function equals($set) {
		$set = Collections::toSet($set);
		return $this->containsAll($set) && $set->containsAll($this);
	}

	/**
	 * @return bool
	 */
	public function isEmpty() {
		return $this->size() <= 0;
	}

	/**
	 * @return \Iterator
	 */
	public function iterator() {
		return $this->getIterator();
	}

	/**
	 * @param mixed $item
	 * @return ArraySet
	 */
	public function remove($item) {
		$index = array_search($item, $this->items);
		if ($index !== false) {
			unset($this->items[$index]);
		}
		return $this;
	}

	/**
	 * @param array|ArraySet $items
	 * @return ArraySet
	 */
	public function removeAll($items) {
		$items = Collections::toSet($items);
		foreach ($items as $item) {
			$this->remove($item);
		}
		return $this;
	}

	/**
	 * @param array|ArraySet $items
	 * @return ArraySet
	 */
	public function retainAll($items) {
		$items = Collections::toSet($items);
		return $this->removeAll($this->exclusiveOr($items));
	}

	/**
	 * @return int
	 */
	public function size() {
		return $this->count();
	}

	/**
	 * @return array
	 */
	public function toArray() {
		$array = $this->items;
		return $array;
	}

	// PHP functions

	/**
	 * @return int
	 */
	public function count() {
		return count($this->items);
	}

	/**
	 * @return \Iterator
	 */
	public function getIterator() {
		return new IteratorIterator($this->items);
	}

	public function serialize() {
		throw new LogicException("Not implemented yet.");
	}

	public function unserialize($serialized) {
		throw new LogicException("Not implemented yet.");
	}

	// Own functions

	/**
	 * @param array|ArraySet $items
	 * @return ArraySet
	 */
	public function intersect($items) {
		$items = Collections::toSet($items);
		return Collections::toSet(array_intersect($this->items, $items->items));
	}

	/**
	 * @param array|ArraySet $items
	 * @return ArraySet
	 */
	public function exclusiveOr($items) {
		$items = Collections::toSet($items);
		return Collections::toSet(array_merge(array_diff($this->items, $items->items), array_diff($items->items, $this->items)));
	}

}
