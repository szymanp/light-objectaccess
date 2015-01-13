<?php
namespace Light\ObjectAccess\Type\Collection;

final class Element
{
	/** @var boolean */
	private $exists;
	/** @var mixed */
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
	 * @param mixed	$value
	 * @return Element
	 */
	public static function value($value)
	{
		$element = new self;
		$element->exists = true;
		$element->value = $value;
		return $element;
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
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

}