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

}
