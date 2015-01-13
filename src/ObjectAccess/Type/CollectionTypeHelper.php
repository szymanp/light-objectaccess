<?php
namespace Light\ObjectAccess\Type;

class CollectionTypeHelper extends TypeHelper
{
	/**
	 * @return CollectionType
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
		return $this->getType()->isValidValue($this->typeRegistry, $value);
	}
}