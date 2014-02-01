<?php

namespace stekycz\collections;

/**
 * General library class for common methods.
 */
class Collections
{

	/**
	 * Transforms given array or set into ArraySet instance.
	 *
	 * @param array|\stekycz\collections\ICollection $items
	 * @return \stekycz\collections\ArraySet
	 * @throws \stekycz\collections\InvalidArgumentException
	 */
	public static function toSet($items)
	{
		static::checkValidType($items);

		return static::isSetType($items)
			? clone $items
			: (static::isCollection($items)
				? new ArraySet($items->toArray())
				: new ArraySet($items)
			);
	}



	public static function toArrayObject($items)
	{
		static::checkValidType($items);

		return static::isArrayObjectType($items)
			? clone $items
			: (static::isCollection($items)
				? new ArrayObject($items->toArray())
				: new ArrayObject($items)
			);
	}



	/**
	 * Checks if given items are in array or instance of ArraySet.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 * @throws \stekycz\collections\InvalidArgumentException
	 */
	public static function checkValidType($items)
	{
		if (!static::isArrayType($items) && !static::isCollection($items)) {
			$type = gettype($items);
			$type = $type == 'object' ? get_class($items) : $type;
			throw new InvalidArgumentException(
				"Items must be array or instance of ICollection. Type '" . $type . "' was given."
			);
		}
	}



	/**
	 * Checks if given items are in array.
	 *
	 * @param array $items
	 * @return bool
	 */
	public static function isArrayType($items)
	{
		return is_array($items);
	}



	/**
	 * Checks if given collection is instance of ICollection.
	 *
	 * @param \stekycz\collections\ICollection $collection
	 * @return bool
	 */
	public static function isCollection($collection)
	{
		return ($collection instanceof ICollection);
	}



	/**
	 * Checks if given set is instance of ArraySet.
	 *
	 * @param \stekycz\collections\ArraySet $set
	 * @return bool
	 */
	public static function isSetType($set)
	{
		return ($set instanceof ArraySet);
	}



	/**
	 * Checks if given set is instance of ArrayObject.
	 *
	 * @param \stekycz\collections\ArrayObject $array
	 * @return bool
	 */
	public static function isArrayObjectType($array)
	{
		return ($array instanceof ArrayObject);
	}

}
