<?php
namespace Light\ObjectAccess\Type;

class SimpleTypeHelper extends TypeHelper
{
	const CLASSNAME = 'Light\ObjectAccess\Type\SimpleTypeHelper';

	/**
	 * @return SimpleType
	 */
	public function getType()
	{
		return parent::getType();
	}

	/**
	 * Returns true if the given value is valid for this type.
	 * @param mixed $value
	 * @return boolean
	 */
	public function isValidValue($value)
	{
		return $this->getType()->isValidValue($value);
	}

}