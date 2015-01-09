<?php
namespace Light\ObjectAccess\Type;

interface CollectionType extends Type
{
	/**
	 * Returns the type of items in this collection.
	 * @return Type
	 */
	public function getBaseType();
}