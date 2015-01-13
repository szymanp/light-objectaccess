<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Type\Collection\Element;

interface CollectionType extends Type
{
	/**
	 * Returns the type name of items in this collection.
	 * @return string
	 */
	public function getBaseTypeName();

	/**
	 * Returns an element from the given collection at the specified offset.
	 * @param ResolvedCollection 	$coll
	 * @param string|integer		$key
	 * @return Element
	 */
	public function getElementAtOffset(ResolvedCollection $coll, $key);

	/**
	 * Returns true if the given value can be handled by this type.
	 * @param TypeRegistry $typeRegistry
	 * @param mixed	$value					The value to be tested.
	 * @return boolean
	 */
	public function isValidValue(TypeRegistry $typeRegistry, $value);
}