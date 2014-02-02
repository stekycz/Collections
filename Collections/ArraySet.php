<?php

namespace stekycz\collections;

use ArrayIterator;



/**
 * Represents collection of unique values.
 */
class ArraySet implements ICollection
{

	/**
	 * @var \stekycz\collections\ArrayObject
	 */
	private $items;



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
	 * @return \stekycz\collections\ArraySet
	 */
	public function add($item)
	{
		if (!$this->contains($item)) {
			$this->items->add($item);
		}

		return $this;
	}



	/**
	 * @param array|\stekycz\collections\ICollection $items
	 * @return \stekycz\collections\ArraySet
	 */
	public function addAll($items)
	{
		Collections::checkValidType($items);
		$items = Collections::isArrayType($items) ? $items : $items->toArray();
		$this->items->addAll(array_values($items));
		$this->items = $this->items->unique();

		return $this;
	}



	/**
	 * @return \stekycz\collections\ArraySet
	 */
	public function clear()
	{
		$this->items = new ArrayObject();;

		return $this;
	}



	/**
	 * @param mixed $item
	 * @return bool
	 */
	public function contains($item)
	{
		return $this->items->contains($item);
	}



	/**
	 * @param array|\stekycz\collections\ICollection $items
	 * @return bool
	 */
	public function containsAll($items)
	{
		$items = Collections::toSet($items);

		return $this->items->containsAll($items);
	}



	/**
	 * @param array|\stekycz\collections\ICollection $collection
	 * @return bool
	 */
	public function equals($collection)
	{
		try {
			$collection = Collections::toSet($collection);
		} catch (InvalidArgumentException $e) {
			return FALSE;
		}

		return $this->items->equals($collection);
	}



	/**
	 * @return bool
	 */
	public function isEmpty()
	{
		return $this->items->isEmpty();
	}



	/**
	 * @param mixed $item
	 * @return \stekycz\collections\ArraySet
	 */
	public function remove($item)
	{
		$this->items->remove($item);

		return $this;
	}



	/**
	 * @param array|\stekycz\collections\ICollection $items
	 * @return \stekycz\collections\ArraySet
	 */
	public function removeAll($items)
	{
		$items = Collections::toSet($items);
		$this->items->removeAll($items);

		return $this;
	}



	/**
	 * Retains all items in the collection which are in given collection.
	 *
	 * @param array|\stekycz\collections\ICollection $items
	 * @return \stekycz\collections\ArraySet
	 */
	public function retainAll($items)
	{
		Collections::checkValidType($items);

		return $this->removeAll($this->exclusiveOr($items));
	}



	/**
	 * @return array
	 */
	public function toArray()
	{
		return $this->items->toArray();
	}



	/**
	 * @return int
	 */
	public function count()
	{
		return $this->items->count();
	}



	/**
	 * @return \Iterator
	 */
	public function getIterator()
	{
		return $this->items->getIterator();
	}



	/**
	 * Should return the string representation of the object.
	 *
	 * @return string
	 */
	public function serialize()
	{
		return serialize($this->items);
	}



	/**
	 * Called during unserialization of the object.
	 *
	 * @param string $serialized
	 * @return void
	 */
	public function unserialize($serialized)
	{
		$this->items = unserialize($serialized);
	}

	// Own functions

	/**
	 * Returns intersection of given collection and current collection.
	 * Returned object is new.
	 *
	 * @param array|\stekycz\collections\ICollection $items
	 * @return \stekycz\collections\ArraySet
	 */
	public function intersect($items)
	{
		$items = Collections::toSet($items);

		return Collections::toSet(array_intersect($this->items->toArray(), $items->toArray()));
	}



	/**
	 * Returns result of XOR function on given collection and current collection.
	 * Returned object is new.
	 *
	 * @param array|\stekycz\collections\ICollection $items
	 * @return \stekycz\collections\ArraySet
	 */
	public function exclusiveOr($items)
	{
		$items = Collections::toSet($items);

		return Collections::toSet(array_merge(array_diff($this->toArray(), $items->toArray()), array_diff($items->toArray(), $this->toArray())));
	}

}
