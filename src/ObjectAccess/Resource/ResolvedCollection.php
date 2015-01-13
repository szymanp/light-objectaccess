<?php
namespace Light\ObjectAccess\Resource;

interface ResolvedCollection
{
	/**
	 * Returns the type of the value.
	 * @return CollectionType
	 */
	public function getType();

	/**
	 * @return CollectionTypeHelper
	 */
	public function getTypeHelper();
}