<?php
namespace Light\ObjectAccess\Type;

interface TypeProvider extends NameProvider
{
	/**
	 * Returns a Type object appropriate for the given value.
	 * @param mixed $value
	 * @return Type    A Type object appropriate for the value, if available; otherwise, NULL.
	 */
	public function getTypeByValue($value);

	/**
	 * Returns a Type corresponding to the given name.
	 * @param string $typeName A name of the type. For example, string[].
	 * @return Type A Type object corresponding to the type name, if available; otherwise, NULL.
	 */
	public function getTypeByName($typeName);
}