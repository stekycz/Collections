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
	 * @var \stekycz\collections\ArraySet
	 */
	private $set;

	protected function setUp() {
		parent::setUp();
		$this->set = new ArraySet();
	}

	/**
	 * @test
	 */
	public function createsEmptySet() {
		$this->assertTrue($this->set->isEmpty());
	}

	/**
	 * @test
	 */
	public function addsOneItem() {
		$this->set->add('test');
		$this->assertFalse($this->set->isEmpty());
		$this->assertEquals($this->set->count(), 1);
	}

	/**
	 * @test
	 */
	public function addingOneItemAsDuplicateDoesNotChangeSet() {
		$this->set->add('test');
		$this->assertFalse($this->set->isEmpty());
		$this->assertEquals($this->set->count(), 1);
		$this->set->add('test');
		$this->assertFalse($this->set->isEmpty());
		$this->assertEquals($this->set->count(), 1);
	}

	/**
	 * @test
	 */
	public function isAbleToClearItself() {
		$this->set->add('test 1');
		$this->set->add('test 2');
		$this->assertFalse($this->set->isEmpty());
		$this->set->clear();
		$this->assertTrue($this->set->isEmpty());
	}

}
