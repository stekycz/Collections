<?php
/**
 * @author Martin Štekl <martin.stekl@gmail.com>
 * @copyright Copyright 2013, Martin Štekl
 * @license MIT
 * @version 0.1.0
 */

namespace stekycz\collections;

/**
 * General library class for common methods.
 */
class Collections {

	/**
	 * Transforms given array or set into ArraySet instance.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 * @return \stekycz\collections\ArraySet
	 * @throws \stekycz\collections\InvalidArgumentException
	 */
	public static function toSet($items) {
		static::checkValidType($items);
		return static::isArrayType($items) ? new ArraySet($items) : clone $items;
	}

	/**
	 * Checks if given items are in array or instance of ArraySet.
	 *
	 * @param array|\stekycz\collections\ArraySet $items
	 * @throws \stekycz\collections\InvalidArgumentException
	 */
	public static function checkValidType($items) {
		if (!static::isArrayType($items) && !static::isSetType($items)) {
			$type = gettype($items);
			$type = $type == 'object' ? get_class($items) : $type;
			throw new InvalidArgumentException("Items must be array or instance of ArraySet. Type " . $type . " was given.");
		}
	}

	/**
	 * Checks if given items are in array.
	 *
	 * @param array $items
	 * @return bool
	 */
	public static function isArrayType($items) {
		return is_array($items);
	}

	/**
	 * Checks if given items are instance of ArraySet.
	 *
	 * @param ArraySet $set
	 * @return bool
	 */
	public static function isSetType($set) {
		return ($set instanceof ArraySet);
	}

}
