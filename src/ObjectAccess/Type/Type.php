<?php
namespace Light\ObjectAccess\Type;

/**
 * A base interface for types.
 */
interface Type
{
	/**
	 * Returns true if the given value can be handled by this type.
	 * @param mixed	$value
	 * @return boolean
	 */
	public function isValidValue($value);
}