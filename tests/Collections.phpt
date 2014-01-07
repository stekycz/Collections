<?php

namespace stekycz\collections\tests;

use stdClass;
use stekycz\collections\ArraySet;
use stekycz\collections\Collections;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . "/bootstrap.php";



/**
 * Tests for Collections class.
 */
class CollectionsTest extends TestCase
{

	/**
	 * @dataProvider providesIsArrayTypeData
	 */
	public function testIsArrayType($items, $expected)
	{
		Assert::equal($expected, Collections::isArrayType($items));
	}



	public function providesIsArrayTypeData()
	{
		return array(
			array(array(), TRUE),
			array(array(1, 2, 3), TRUE),
			array(new ArraySet(), FALSE),
			array(new DummyCollection(), FALSE),
			array(NULL, FALSE),
			array(TRUE, FALSE),
			array(FALSE, FALSE),
			array(1, FALSE),
			array(2.3, FALSE),
			array("string", FALSE),
			array(new stdClass(), FALSE),
		);
	}



	/**
	 * @dataProvider providesIsCollectionData
	 */
	public function testIsCollection($items, $expected)
	{
		Assert::equal($expected, Collections::isCollection($items));
	}



	public function providesIsCollectionData()
	{
		return array(
			array(new ArraySet(), TRUE),
			array(new DummyCollection(), TRUE),
			array(array(), FALSE),
			array(array(1, 2, 3), FALSE),
			array(NULL, FALSE),
			array(TRUE, FALSE),
			array(FALSE, FALSE),
			array(1, FALSE),
			array(2.3, FALSE),
			array("string", FALSE),
			array(new stdClass(), FALSE),
		);
	}



	/**
	 * @dataProvider providesIsSetTypeData
	 */
	public function testIsSetType($items, $expected)
	{
		Assert::equal($expected, Collections::isSetType($items));
	}



	public function providesIsSetTypeData()
	{
		return array(
			array(new ArraySet(), TRUE),
			array(new DummyCollection(), FALSE),
			array(array(), FALSE),
			array(array(1, 2, 3), FALSE),
			array(NULL, FALSE),
			array(TRUE, FALSE),
			array(FALSE, FALSE),
			array(1, FALSE),
			array(2.3, FALSE),
			array("string", FALSE),
			array(new stdClass(), FALSE),
		);
	}



	/**
	 * @dataProvider providesIsArrayTypeDataOk
	 */
	public function testCheckValidTypeOk($items)
	{
		Collections::checkValidType($items);
	}



	public function providesIsArrayTypeDataOk()
	{
		return array(
			array(array()),
			array(array(1, 2, 3)),
			array(new ArraySet()),
			array(new DummyCollection()),
		);
	}



	/**
	 * @dataProvider providesIsArrayTypeDataWrong
	 */
	public function testCheckValidTypeException($items)
	{
		Assert::exception(function () use ($items) {
			Collections::checkValidType($items);
		}, '\stekycz\collections\InvalidArgumentException');
	}



	public function providesIsArrayTypeDataWrong()
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



	/**
	 * @dataProvider providesToSetDataOk
	 */
	public function testToSetOk($data)
	{
		$set = Collections::toSet($data);
		Assert::type('\stekycz\collections\ArraySet', $set);
	}



	public function providesToSetDataOk()
	{
		return array(
			array(array()),
			array(array(1, 2, 3)),
			array(new ArraySet()),
			array(new DummyCollection()),
		);
	}



	/**
	 * @dataProvider providesToSetDataWrong
	 */
	public function testToSetException($data)
	{
		Assert::exception(function () use ($data) {
			$set = Collections::toSet($data);
		}, '\stekycz\collections\InvalidArgumentException');
	}



	public function providesToSetDataWrong()
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



\run(new CollectionsTest());
