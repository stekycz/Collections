<?php

namespace stekycz\collections;

use Countable;
use Serializable;
use IteratorAggregate;

interface ICollection extends Countable, IteratorAggregate, Serializable {

	// Java inspiration functions

	/**
	 * Adds given item into collection.
	 *
	 * @param mixed $item
	 * @return \stekycz\collections\ICollection
	 */
	public function add($item);

	/**
	 * Adds all given items into collection.
	 *
	 * @param array|\stekycz\collections\ICollection $items
	 * @return \stekycz\collections\ICollection
	 */
	public function addAll($items);

	/**
	 * Cleans collection.
	 *
	 * @return \stekycz\collections\ICollection
	 */
	public function clear();

	/**
	 * Checks if given item is in collection.
	 *
	 * @param mixed $item
	 * @return bool
	 */
	public function contains($item);

	/**
	 * Checks if all given items are in collection.
	 *
	 * @param array|\stekycz\collections\ICollection $items
	 * @return bool
	 */
	public function containsAll($items);

	/**
	 * Checks if given collection is equal to current collection.
	 *
	 * @param array|\stekycz\collections\ICollection $collection
	 * @return bool
	 */
	public function equals($collection);

	/**
	 * Checks if collection is empty.
	 *
	 * @return bool
	 */
	public function isEmpty();

	/**
	 * Removes given item from collection.
	 *
	 * @param mixed $item
	 * @return \stekycz\collections\ICollection
	 */
	public function remove($item);

	/**
	 * Removes all given items from collection.
	 *
	 * @param array|\stekycz\collections\ICollection $items
	 * @return \stekycz\collections\ICollection
	 */
	public function removeAll($items);

	/**
	 * Returns items in collection as array.
	 *
	 * @return array
	 */
	public function toArray();

	// PHP functions

	/**
	 * Returns count of items in collection.
	 *
	 * @return int
	 */
	public function count();

	/**
	 * Returns iterator over items in collection.
	 *
	 * @return \Iterator
	 */
	public function getIterator();

	/**
	 * Should return the string representation of the object.
	 *
	 * @return string
	 */
	public function serialize();

	/**
	 * Called during unserialization of the object.
	 *
	 * @param string $serialized
	 * @return void
	 */
	public function unserialize($serialized);

}
