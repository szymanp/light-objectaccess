<?php
namespace Light\ObjectAccess\Type;

interface SimpleType extends Type
{
	/**
	 * Returns the PHP type of the value supported by this SimpleType.
	 * @return string
	 */
	public function getPhpType();

	/**
	 * Returns true if the given value can be handled by this type.
	 * @param mixed	$value
	 * @return boolean
	 */
	public function isValidValue($value);
}