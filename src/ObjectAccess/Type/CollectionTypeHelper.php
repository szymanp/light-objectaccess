<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Resource\ResolvedValue;

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

	/**
	 * Returns an element from the collection at the given offset.
	 * @param ResolvedCollection $coll
	 * @param                    $key
	 * @return ResolvedValue	A ResolvedValue object, if the element exists;
	 *                       	otherwise, NULL.
	 */
	public function getElementAtOffset(ResolvedCollection $coll, $key)
	{
		// TODO
	}
}