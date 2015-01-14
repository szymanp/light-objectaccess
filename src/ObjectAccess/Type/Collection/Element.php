<?php
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Type\Complex\Value;

/**
 * A wrapper for an element in a collection.
 */
final class Element
{
	/** @var boolean */
	private $exists;
	/** @var Value */
	private $value;

	/**
	 * A collection element that does not exist.
	 * @return Element
	 */
	public static function notExists()
	{
		$element = new self;
		$element->exists = false;
		return $element;
	}

	/**
	 * A collection element.
	 * @param Value	$value	The wrapped value.
	 * @return Element
	 */
	public static function value(Value $value)
	{
		$element = new self;
		$element->exists = true;
		$element->value = $value;
		return $element;
	}

	/**
	 * A collection element.
	 * @param mixed $value	The actual value.
	 * @return Element
	 */
	public static function valueOf($value)
	{
		return self::value(Value::of($value));
	}

	private function __construct()
	{
		// Empty
	}

	/**
	 * Returns true if this element exists.
	 * @return boolean
	 */
	public function exists()
	{
		return $this->exists;
	}

	/**
	 * Returns the value of this element.
	 * @return Value
	 */
	public function getValue()
	{
		return $this->value;
	}

}