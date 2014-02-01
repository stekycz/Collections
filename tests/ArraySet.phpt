<?php

/**
 * @testCase
 */

namespace stekycz\collections\tests;

use stdClass;
use stekycz\collections\ArraySet;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . "/bootstrap.php";



/**
 * Tests for ArraySet class.
 */
class ArraySetTest extends TestCase
{

	public function testCreatesEmptySetByDefault()
	{
		$set = new ArraySet();
		Assert::true($set->isEmpty());
	}



	public function testCreatesSetFromArray()
	{
		$set = new ArraySet(array('test 1', 'test 2', 'test 1',));
		Assert::false($set->isEmpty());
		Assert::equal(2, $set->count());
	}



	public function testAddsOneItem()
	{
		$set = new ArraySet();
		$set->add('test');
		Assert::false($set->isEmpty());
		Assert::equal(1, $set->count());
	}



	public function testAddingOneItemAsDuplicateDoesNotChangeSet()
	{
		$set = new ArraySet();
		$set->add('test');
		Assert::false($set->isEmpty());
		Assert::equal(1, $set->count());
		$set->add('test');
		Assert::false($set->isEmpty());
		Assert::equal(1, $set->count());
	}



	public function testReturnsIterator()
	{
		$set = new ArraySet(array('test 1', 'test 2', 'test 1',));
		Assert::type('\Iterator', $set->getIterator());
	}



	public function testIsAbleToClearItself()
	{
		$set = new ArraySet(array('test 1', 'test 2',));
		Assert::false($set->isEmpty());
		$set->clear();
		Assert::true($set->isEmpty());
	}



	/**
	 * @dataProvider providesDataForArrayReturnChecking
	 */
	public function testIsAbleToReturnItemsAsArray($items)
	{
		$set = new ArraySet($items);
		Assert::equal(count(array_unique($items)), $set->count());
		$array = $set->toArray();
		Assert::true(is_array($array));
		// There is no need to preserve keys in set
		Assert::equal(array_values($items), array_values($array));
		Assert::equal(array_keys(array_values($items)), array_keys($array));
	}



	public function providesDataForArrayReturnChecking()
	{
		return array(
			array(array()),
			array(array('test 1', 'test 2',)),
			array(array('a' => 'test 1', 2 => 'test 2',)),
		);
	}



	/**
	 * @dataProvider providesDataForContainsChecking
	 */
	public function testChecksIfContainsGivenItem($expected, $item)
	{
		$set = new ArraySet(array('test 1', 'test 2',));
		Assert::equal($expected, $set->contains($item));
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
		$set = new ArraySet(array('test 1', 'test 2',));
		Assert::equal($expected, $set->containsAll($items));
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
	public function testComparesTwoSetsToBeEqual($expected, $items)
	{
		$set = new ArraySet(array('test 1', 'test 2',));
		Assert::equal($expected, $set->equals($items));
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
		$set = new ArraySet(array('test 1', 'test 2',));
		Assert::equal(2, $set->count());
		$set->remove('test 1');
		Assert::equal(1, $set->count());
		$set->remove('test 3');
		Assert::equal(1, $set->count());
		$set->remove('test 2');
		Assert::true($set->isEmpty());
	}



	/**
	 * @dataProvider providesDataForAddingAllItems
	 */
	public function testAddsAllGivenItems($items)
	{
		$set = new ArraySet();
		$set->addAll($items);
		Assert::true($set->containsAll($items));
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
		$set = new ArraySet(array('test 1', 'test 2',));
		$set->removeAll($items);
		Assert::true($set->containsAll($expectedRetained));
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
		$set = new ArraySet(array('test 1', 'test 2',));

		$serialized = serialize($set);
		Assert::true(is_string($serialized));

		$unserialized = unserialize($serialized);
		Assert::equal($set, $unserialized);
	}



	/**
	 * @dataProvider providesDataForIntersection
	 */
	public function testProvidesIntersection($expected, $items1, $items2)
	{
		$set1 = new ArraySet($items1);
		$set2 = new ArraySet($items2);
		Assert::true($set1->intersect($set2)->equals($expected));
	}



	public function providesDataForIntersection()
	{
		return array(
			array(array(), array('test 1',), array('test 2',)),
			array(array(), array(), array()),
			array(array('test 2',), array('test 1', 'test 2',), array('test 2',)),
			array(array('test 1',), array('test 1',), array('test 1', 'test 2',)),
		);
	}



	/**
	 * @dataProvider providesDataForExclusiveOr
	 */
	public function testProvidesExclusiveOr($expected, $items1, $items2)
	{
		$set1 = new ArraySet($items1);
		$set2 = new ArraySet($items2);
		Assert::true($set1->exclusiveOr($set2)->equals($expected));
	}



	public function providesDataForExclusiveOr()
	{
		return array(
			array(array('test 1', 'test 2',), array('test 1',), array('test 2',)),
			array(array(), array(), array()),
			array(array('test 1',), array('test 1', 'test 2',), array('test 2',)),
			array(array('test 2',), array('test 1',), array('test 1', 'test 2',)),
		);
	}



	/**
	 * @dataProvider providesDataForRetainingAllItems
	 */
	public function testIsAbleToRetainAllGivenItems($expected, $retainItems, $items)
	{
		$set = new ArraySet($items);
		Assert::true($set->retainAll($retainItems)->equals($expected));
	}



	public function providesDataForRetainingAllItems()
	{
		return array(
			array(array('test 1',), array('test 1',), array('test 1', 'test 2',)),
			array(array(), array(), array('test 1', 'test 2',)),
			array(array(), array(), array()),
			array(array('test 2',), array('test 2',), array('test 1', 'test 2',)),
			array(array(), array('test 3',), array('test 1', 'test 2',)),
		);
	}



	/**
	 * @dataProvider providesWrongData
	 */
	public function testThrowsExceptionWhenInvalidArgumentGivenToConstructor($items)
	{
		Assert::exception(function () use ($items) {
			$set = new ArraySet($items);
		}, '\stekycz\collections\InvalidArgumentException');
	}



	/**
	 * @dataProvider providesWrongData
	 */
	public function testThrowsExceptionWhenInvalidArgumentGivenToAddAll($items)
	{
		Assert::exception(function () use ($items) {
			$set = new ArraySet();
			$set->addAll($items);
		}, '\stekycz\collections\InvalidArgumentException');
	}



	/**
	 * @dataProvider providesWrongData
	 */
	public function testThrowsExceptionWhenInvalidArgumentGivenToContainsAll($items)
	{
		Assert::exception(function () use ($items) {
			$set = new ArraySet();
			$set->containsAll($items);
		}, '\stekycz\collections\InvalidArgumentException');
	}



	/**
	 * @dataProvider providesWrongData
	 */
	public function testReturnsFalseWhenInvalidArgumentGivenToEquals($items)
	{
		$set = new ArraySet();
		Assert::false($set->equals($items));
	}



	/**
	 * @dataProvider providesWrongData
	 */
	public function testThrowsExceptionWhenInvalidArgumentGivenToRemoveAll($items)
	{
		Assert::exception(function () use ($items) {
			$set = new ArraySet();
			$set->removeAll($items);
		}, '\stekycz\collections\InvalidArgumentException');
	}



	/**
	 * @dataProvider providesWrongData
	 */
	public function testThrowsExceptionWhenInvalidArgumentGivenToRetainAll($items)
	{
		Assert::exception(function () use ($items) {
			$set = new ArraySet();
			$set->retainAll($items);
		}, '\stekycz\collections\InvalidArgumentException');
	}



	/**
	 * @dataProvider providesWrongData
	 */
	public function testThrowsExceptionWhenInvalidArgumentGivenToIntersect($items)
	{
		Assert::exception(function () use ($items) {
			$set = new ArraySet();
			$set->intersect($items);
		}, '\stekycz\collections\InvalidArgumentException');
	}



	/**
	 * @dataProvider providesWrongData
	 */
	public function testThrowsExceptionWhenInvalidArgumentGivenToExclusiveOr($items)
	{
		Assert::exception(function () use ($items) {
			$set = new ArraySet();
			$set->exclusiveOr($items);
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



\run(new ArraySetTest());
