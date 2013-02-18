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
	public function createsEmptySet() {
		$set = new ArraySet();
		$this->assertTrue($set->isEmpty());
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
	public function createsSetFromArray() {
		$set = new ArraySet(array('test 1', 'test 2', 'test 1', ));
		$this->assertFalse($set->isEmpty());
		$this->assertEquals(2, $set->count());
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
	 */
	public function isAbleToReturnItemsAsArray() {
		$items = array('test 1', 'test 2', );
		$set = new ArraySet($items);
		$this->assertFalse($set->isEmpty());
		$array = $set->toArray();
		$this->assertTrue(is_array($array));
		$this->assertEquals($items, $array);
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

		$this->assertFalse($set->contains(array('test 3', )));
		$this->assertFalse($set->contains(array('test 1', 'test 2', 'test 3', )));
		$this->assertTrue($set->contains(array()));
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

}
