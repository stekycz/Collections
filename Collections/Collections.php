<?php
/**
 * @author Martin Štekl <martin.stekl@gmail.com>
 * @copyright Copyright 2013, Martin Štekl
 * @license MIT
 * @version 0.1.0
 */

namespace stekycz\collections;

class Collections {

	/**
	 * @param array|ArraySet $items
	 * @return ArraySet
	 */
	public static function toSet($items) {
		static::checkValidType($items);
		return static::isArrayType($items) ? new ArraySet($items) : clone $items;
	}

	/**
	 * @param array|ArraySet $items
	 * @throws InvalidArgumentException
	 */
	public static function checkValidType($items) {
		if (!static::isArrayType($items) && !static::isSetType($items)) {
			$type = gettype($items);
			$type = $type == 'object' ? get_class($items) : $type;
			throw new InvalidArgumentException("Items must be array or instance of ArraySet. Type " . $type . " was given.");
		}
	}

	/**
	 * @param array $items
	 * @return bool
	 */
	public static function isArrayType($items) {
		return is_array($items);
	}

	/**
	 * @param ArraySet $set
	 * @return bool
	 */
	public static function isSetType($set) {
		return ($set instanceof ArraySet);
	}

}
