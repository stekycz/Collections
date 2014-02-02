<?php

/**
 * @testCase
 */

namespace stekycz\collections\tests;

use stdClass;
use stekycz\collections\ArrayObject;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . "/bootstrap.php";



/**
 * Tests for ArrayObject class.
 */
class ArrayObjectTest extends TestCase
{

	public function testCreatesEmptySetByDefault()
	{
		$array = new ArrayObject();
		Assert::true($array->isEmpty());
	}



	public function testCreatesArrayObjectFromArray()
	{
		$array = new ArrayObject(array('test 1', 'test 2', 'test 1',));
		Assert::false($array->isEmpty());
		Assert::equal(3, $array->count());
	}



	public function testAddsOneItem()
	{
		$array = new ArrayObject();
		$array->add('test');
		Assert::false($array->isEmpty());
		Assert::equal(1, $array->count());
	}



	public function testAddingOneItemAsDuplicateDoesChangeArray()
	{
		$array = new ArrayObject();
		$array->add('test');
		Assert::false($array->isEmpty());
		Assert::equal(1, $array->count());
		$array->add('test');
		Assert::false($array->isEmpty());
		Assert::equal(2, $array->count());
	}



	public function testReturnsIterator()
	{
		$array = new ArrayObject(array('test 1', 'test 2', 'test 1',));
		Assert::type('\Iterator', $array->getIterator());
	}



	public function testIsAbleToClearItself()
	{
		$array = new ArrayObject(array('test 1', 'test 2',));
		Assert::false($array->isEmpty());
		$array->clear();
		Assert::true($array->isEmpty());
	}



	/**
	 * @dataProvider providesDataForArrayReturnChecking
	 */
	public function testIsAbleToReturnItemsAsArray($items, $keys)
	{
		$arrayObject = new ArrayObject($items);
		Assert::equal(count($items), $arrayObject->count());
		$array = $arrayObject->toArray();
		Assert::true(is_array($array));
		Assert::equal(array_values($items), array_values($array));
		Assert::equal($keys, array_keys($array));
	}



	public function providesDataForArrayReturnChecking()
	{
		return array(
			array(array(), array()),
			array(array('test 1', 'test 2',), array(0, 1)),
			array(array('a' => 'test 1', 2 => 'test 2',), array('a', 0)),
		);
	}



	/**
	 * @dataProvider providesDataForContainsChecking
	 */
	public function testChecksIfContainsGivenItem($expected, $item)
	{
		$array = new ArrayObject(array('test 1', 'test 2',));
		Assert::equal($expected, $array->contains($item));
	}



	public function providesDataForContainsChecking()
	{
		return array(
			array(TRUE, 'test 1'),
			array(TRUE, 'test 2'),
			array(FALSE, 'test 3'),
			array(FALSE, NULL),
		);
	}



	/**
	 * @dataProvider providesDataForContainsAllChecking
	 */
	public function testChecksIfContainsAllGivenItems($expected, $items)
	{
		$array = new ArrayObject(array('test 1', 'test 2',));
		Assert::equal($expected, $array->containsAll($items));
	}



	public function providesDataForContainsAllChecking()
	{
		return array(
			array(TRUE, array('test 1',)),
			array(TRUE, array('test 2',)),
			array(TRUE, array('test 1', 'test 2',)),
			array(FALSE, array('test 3',)),
			array(FALSE, array('test 1', 'test 2', 'test 3',)),
			array(TRUE, array()),
		);
	}



	/**
	 * @dataProvider providesDataForEqualsChecking
	 */
	public function testComparesTwoArraysToBeEqual($expected, $items)
	{
		$array = new ArrayObject(array('test 1', 'test 2',));
		Assert::equal($expected, $array->equals($items));
	}



	public function providesDataForEqualsChecking()
	{
		return array(
			array(TRUE, array('test 1', 'test 2',)),
			array(FALSE, array()),
			array(FALSE, array('test 1',)),
			array(FALSE, array('test 2',)),
			array(FALSE, array('test 3',)),
			array(FALSE, array('test 1', 'test 2', 'test 3',)),
		);
	}



	public function testRemovesOneItem()
	{
		$array = new ArrayObject(array('test 1', 'test 2',));
		Assert::equal(2, $array->count());
		$array->remove('test 1');
		Assert::equal(1, $array->count());
		$array->remove('test 3');
		Assert::equal(1, $array->count());
		$array->remove('test 2');
		Assert::true($array->isEmpty());
	}



	/**
	 * @dataProvider providesDataForAddingAllItems
	 */
	public function testAddsAllGivenItems($items)
	{
		$array = new ArrayObject();
		$array->addAll($items);
		Assert::true($array->containsAll($items));
	}



	public function providesDataForAddingAllItems()
	{
		return array(
			array(array()),
			array(array('test 1', 'test 2',)),
			array(array('test 1', 'test 2', 'test 1', 'test 2',)),
		);
	}



	/**
	 * @dataProvider providesDataForRemovingAllItems
	 */
	public function testRemovesAllGivenItems($expectedRetained, $items)
	{
		$array = new ArrayObject(array('test 1', 'test 2',));
		$array->removeAll($items);
		Assert::true($array->containsAll($expectedRetained));
	}



	public function providesDataForRemovingAllItems()
	{
		return array(
			array(array('test 1', 'test 2',), array()),
			array(array(), array('test 1', 'test 2',)),
			array(array(), array('test 1', 'test 2', 'test 1', 'test 2',)),
			array(array('test 1',), array('test 2', 'test 3',)),
		);
	}



	public function testIsSerializable()
	{
		$array = new ArrayObject(array('test 1', 'test 2',));

		$serialized = serialize($array);
		Assert::true(is_string($serialized));

		$unserialized = unserialize($serialized);
		Assert::equal($array, $unserialized);
	}



	public function testIsFilterable()
	{
		$array = new ArrayObject(array('test 1', 1, 2.1, TRUE,));
		$filtered = $array->filter(function ($item) {
			return is_int($item) || is_float($item);
		});
		Assert::equal(array(0 => 1, 1 => 2.1,), $filtered->toArray());
	}



	public function testCanMapItsValues()
	{
		$array = new ArrayObject(array(1, 0, TRUE,));
		$filtered = $array->map(function ($item) {
			return (bool) $item;
		});
		Assert::equal(array(TRUE, FALSE, TRUE,), $filtered->toArray());
	}



	public function testCanBeReducedToOneValue()
	{
		$array = new ArrayObject(array(1, 2, 3, 4.5,));
		$reduced = $array->reduce(function ($result, $item) {
			return $result + $item;
		}, 1);
		Assert::equal(11.5, $reduced);
	}



	public function testCanBeMadeUnique()
	{
		$array = new ArrayObject(array(1, 2, 3, 2));
		$unique = $array->unique();
		Assert::equal(array(1, 2, 3), $unique->toArray());
	}



	public function testCanBeShuffled()
	{
		$array = new ArrayObject(array(1, 2, 3, 2));
		$shuffled = $array->shuffle();
		Assert::equal(4, $shuffled->count());
	}



	public function testCanBeIteratedByMethodEach()
	{
		$array = new ArrayObject(array(1, 2, 3));
		$used = array();
		$array->each(function ($item) use (&$used) {
			$used[] = $item;
		});
		Assert::equal(array(1, 2, 3), $used);
	}



	public function testCanBeReversed()
	{
		$array = new ArrayObject(array(3, 1, 2));
		$reversed = $array->reverse();
		Assert::equal(array(0 => 2, 1 => 1, 2 => 3), $reversed->toArray());
	}



	public function testCanBeSorted()
	{
		$array = new ArrayObject(array(1, 3, 2));
		$sorted = $array->sort();
		Assert::equal(array(0 => 1, 1 => 2, 2 => 3), $sorted->toArray());
	}



	public function testCanBeSortedInReverseOrder()
	{
		$array = new ArrayObject(array(1, 3, 2));
		$sorted = $array->sort(NULL, TRUE);
		Assert::equal(array(0 => 3, 1 => 2, 2 => 1), $sorted->toArray());
	}



	public function testCanBeSortedByCallback()
	{
		$array = new ArrayObject(array(1, 3, 2));
		$sorted = $array->sort(function ($a, $b) {
			return $a - $b;
		});
		Assert::equal(array(0 => 1, 1 => 2, 2 => 3), $sorted->toArray());
	}



	public function testCanBeSortedByKeys()
	{
		$array = new ArrayObject(array('a' => 1, 'c' => 3, 'b' => 2));
		$sorted = $array->sort();
		Assert::equal(array('a' => 1, 'b' => 2, 'c' => 3), $sorted->toArray());
	}



	public function testCanBeSortedByKeysInReverseOrder()
	{
		$array = new ArrayObject(array('a' => 1, 'c' => 3, 'b' => 2));
		$sorted = $array->sort(NULL, TRUE);
		Assert::equal(array('c' => 3, 'b' => 2, 'a' => 1), $sorted->toArray());
	}



	public function testCanBeSortedByKeysByCallback()
	{
		$array = new ArrayObject(array('a' => 1, 'c' => 3, 'b' => 2));
		$sorted = $array->sort(function ($a, $b) {
			return strcmp($a, $b);
		});
		Assert::equal(array('a' => 1, 'b' => 2, 'c' => 3), $sorted->toArray());
	}



	public function testSupportsArrayAccess()
	{
		$array = new ArrayObject(array('a' => 1, 'c' => 3, 'b' => 2));
		Assert::equal(1, $array['a']);
		Assert::equal(2, $array['b']);
		Assert::equal(3, $array['c']);
		$array['c'] = 7;
		Assert::equal(7, $array['c']);
		Assert::true(isset($array['a']));
		Assert::false(isset($array['q']));
		unset($array['a']);
		Assert::false(isset($array['a']));
		unset($array['a']); // Should not throw any error
	}



	/**
	 * @dataProvider providesWrongData
	 */
	public function testThrowsExceptionWhenInvalidArgumentGivenToConstructor($items)
	{
		Assert::exception(function () use ($items) {
			$array = new ArrayObject($items);
		}, '\stekycz\collections\InvalidArgumentException');
	}



	/**
	 * @dataProvider providesWrongData
	 */
	public function testThrowsExceptionWhenInvalidArgumentGivenToAddAll($items)
	{
		Assert::exception(function () use ($items) {
			$array = new ArrayObject();
			$array->addAll($items);
		}, '\stekycz\collections\InvalidArgumentException');
	}



	/**
	 * @dataProvider providesWrongData
	 */
	public function testThrowsExceptionWhenInvalidArgumentGivenToContainsAll($items)
	{
		Assert::exception(function () use ($items) {
			$array = new ArrayObject();
			$array->containsAll($items);
		}, '\stekycz\collections\InvalidArgumentException');
	}



	/**
	 * @dataProvider providesWrongData
	 */
	public function testReturnsFalseWhenInvalidArgumentGivenToEquals($items)
	{
		$array = new ArrayObject();
		Assert::false($array->equals($items));
	}



	/**
	 * @dataProvider providesWrongData
	 */
	public function testThrowsExceptionWhenInvalidArgumentGivenToRemoveAll($items)
	{
		Assert::exception(function () use ($items) {
			$array = new ArrayObject();
			$array->removeAll($items);
		}, '\stekycz\collections\InvalidArgumentException');
	}



	public function providesWrongData()
	{
		return array(
			array(NULL),
			array(TRUE),
			array(FALSE),
			array(1),
			array(2.3),
			array("string"),
			array(new stdClass()),
		);
	}

}



\run(new ArrayObjectTest());
