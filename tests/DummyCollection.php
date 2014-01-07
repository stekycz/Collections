<?php

namespace stekycz\collections\tests;

use stekycz\collections\ICollection;



/**
 * Class DummyCollection is used only in tests.
 */
class DummyCollection implements ICollection
{

	public function getIterator()
	{
	}



	public function serialize()
	{
	}



	public function unserialize($serialized)
	{
	}



	public function add($item)
	{
	}



	public function addAll($items)
	{
	}



	public function clear()
	{
	}



	public function contains($item)
	{
	}



	public function containsAll($items)
	{
	}



	public function equals($collection)
	{
	}



	public function isEmpty()
	{
	}



	public function remove($item)
	{
	}



	public function removeAll($items)
	{
	}



	public function toArray()
	{
		return array();
	}



	public function count()
	{
	}

}
