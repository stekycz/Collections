<?php
/**
 * @author Martin Štekl <martin.stekl@gmail.com>
 * @copyright Copyright 2013, Martin Štekl
 * @license MIT
 * @version 0.1.0
 */

namespace stekycz\collections\tests;

use PHPUnit_Framework_TestCase;
use stekycz\collections\ArraySet;

/**
 * Tests for ArraySet class.
 */
class ArraySet_Test extends PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function createsEmptySetByDefault() {
		$set = new ArraySet();
		$this->assertTrue($set->isEmpty());
	}

	/**
	 * @test
	 */
	public function createsSetFromArray() {
		$set = new ArraySet(array('test 1', 'test 2', 'test 1', ));
		$this->assertFalse($set->isEmpty());
		$this->assertEquals(2, $set->count());
	}

	/**
	 * @test
	 */
	public function addsOneItem() {
		$set = new ArraySet();
		$set->add('test');
		$this->assertFalse($set->isEmpty());
		$this->assertEquals(1, $set->count());
	}

	/**
	 * @test
	 */
	public function addingOneItemAsDuplicateDoesNotChangeSet() {
		$set = new ArraySet();
		$set->add('test');
		$this->assertFalse($set->isEmpty());
		$this->assertEquals(1, $set->count());
		$set->add('test');
		$this->assertFalse($set->isEmpty());
		$this->assertEquals(1, $set->count());
	}

	/**
	 * @test
	 */
	public function returnsIterator() {
		$set = new ArraySet(array('test 1', 'test 2', 'test 1', ));
		$this->assertInstanceOf('\Iterator', $set->getIterator());
	}

	/**
	 * @test
	 */
	public function isAbleToClearItself() {
		$set = new ArraySet(array('test 1', 'test 2', ));
		$this->assertFalse($set->isEmpty());
		$set->clear();
		$this->assertTrue($set->isEmpty());
	}

	/**
	 * @test
	 * @dataProvider providesDataForArrayReturnChecking
	 */
	public function isAbleToReturnItemsAsArray($items) {
		$set = new ArraySet($items);
		$this->assertEquals(count(array_unique($items)), $set->count());
		$array = $set->toArray();
		$this->assertTrue(is_array($array));
		$this->assertEquals($items, $array);
	}

	public function providesDataForArrayReturnChecking() {
		return array(
			array(array()),
			array(array('test 1', 'test 2', )),
		);
	}

	/**
	 * @test
	 * @dataProvider providesDataForContainsChecking
	 */
	public function checksIfContainsGivenItem($expected, $item) {
		$set = new ArraySet(array('test 1', 'test 2', ));
		$this->assertEquals($expected, $set->contains($item));
	}

	public function providesDataForContainsChecking() {
		return array(
			array(true, 'test 1'),
			array(true, 'test 2'),
			array(false, 'test 3'),
			array(false, null),
		);
	}

	/**
	 * @test
	 * @dataProvider providesDataForContainsAllChecking
	 */
	public function checksIfContainsAllGivenItems($expected, $items) {
		$set = new ArraySet(array('test 1', 'test 2', ));
		$this->assertEquals($expected, $set->containsAll($items));
	}

	public function providesDataForContainsAllChecking() {
		return array(
			array(true, array('test 1', )),
			array(true, array('test 2', )),
			array(true, array('test 1', 'test 2', )),
			array(false, array('test 3', )),
			array(false, array('test 1', 'test 2', 'test 3', )),
			array(true, array()),
		);
	}

	/**
	 * @test
	 * @dataProvider providesDataForEqualsChecking
	 */
	public function comparesTwoSetsToBeEqual($expected, $items) {
		$set = new ArraySet(array('test 1', 'test 2', ));
		$this->assertEquals($expected, $set->equals($items));
	}

	public function providesDataForEqualsChecking() {
		return array(
			array(true, array('test 1', 'test 2', )),
			array(false, array()),
			array(false, array('test 1', )),
			array(false, array('test 2', )),
			array(false, array('test 3', )),
			array(false, array('test 1', 'test 2', 'test 3', )),
		);
	}

	/**
	 * @test
	 */
	public function removesOneItem() {
		$set = new ArraySet(array('test 1', 'test 2', ));
		$this->assertEquals(2, $set->count());
		$set->remove('test 1');
		$this->assertEquals(1, $set->count());
		$set->remove('test 3');
		$this->assertEquals(1, $set->count());
		$set->remove('test 2');
		$this->assertTrue($set->isEmpty());
	}

	/**
	 * @test
	 * @dataProvider providesDataForAddingAllItems
	 */
	public function addsAllGivenItems($items) {
		$set = new ArraySet();
		$set->addAll($items);
		$this->assertTrue($set->containsAll($items));
	}

	public function providesDataForAddingAllItems() {
		return array(
			array(array()),
			array(array('test 1', 'test 2', )),
			array(array('test 1', 'test 2', 'test 1', 'test 2', )),
		);
	}

	/**
	 * @test
	 * @dataProvider providesDataForRemovingAllItems
	 */
	public function removesAllGivenItems($expectedRetained, $items) {
		$set = new ArraySet(array('test 1', 'test 2', ));
		$set->removeAll($items);
		$this->assertTrue($set->containsAll($expectedRetained));
	}

	public function providesDataForRemovingAllItems() {
		return array(
			array(array('test 1', 'test 2', ), array()),
			array(array(), array('test 1', 'test 2', )),
			array(array(), array('test 1', 'test 2', 'test 1', 'test 2', )),
			array(array('test 1', ), array('test 2', 'test 3', )),
		);
	}

	/**
	 * @test
	 */
	public function isSerializable() {
		$set = new ArraySet(array('test 1', 'test 2', ));

		$serialized = serialize($set);
		$this->assertTrue(is_string($serialized));

		$unserialized = unserialize($serialized);
		$this->assertEquals($set, $unserialized);
	}

	/**
	 * @test
	 * @dataProvider providesDataForIntersection
	 */
	public function providesIntersection($expected, $items1, $items2) {
		$set1 = new ArraySet($items1);
		$set2 = new ArraySet($items2);
		$this->assertTrue($set1->intersect($set2)->equals($expected));
	}

	public function providesDataForIntersection() {
		return array(
			array(array(), array('test 1', ), array('test 2', )),
			array(array(), array(), array()),
			array(array('test 2', ), array('test 1', 'test 2', ), array('test 2', )),
			array(array('test 1', ), array('test 1', ), array('test 1', 'test 2', )),
		);
	}

	/**
	 * @test
	 * @dataProvider providesDataForExclusiveOr
	 */
	public function providesExclusiveOr($expected, $items1, $items2) {
		$set1 = new ArraySet($items1);
		$set2 = new ArraySet($items2);
		$this->assertTrue($set1->exclusiveOr($set2)->equals($expected));
	}

	public function providesDataForExclusiveOr() {
		return array(
			array(array('test 1', 'test 2', ), array('test 1', ), array('test 2', )),
			array(array(), array(), array()),
			array(array('test 1', ), array('test 1', 'test 2', ), array('test 2', )),
			array(array('test 2', ), array('test 1', ), array('test 1', 'test 2', )),
		);
	}

	/**
	 * @test
	 * @dataProvider providesDataForRetainingAllItems
	 */
	public function isAbleToRetainAllGivenItems($expected, $retainItems, $items) {
		$set = new ArraySet($items);
		$this->assertTrue($set->retainAll($retainItems)->equals($expected));
	}

	public function providesDataForRetainingAllItems() {
		return array(
			array(array('test 1', ), array('test 1', ), array('test 1', 'test 2', )),
			array(array(), array(), array('test 1', 'test 2', )),
			array(array(), array(), array()),
			array(array('test 2', ), array('test 2', ), array('test 1', 'test 2', )),
			array(array(), array('test 3', ), array('test 1', 'test 2', )),
		);
	}

}
