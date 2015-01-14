<?php
namespace Light\ObjectAccess\Resource;

use Light\ObjectAccess\Resource\Addressing\ResourceAddress;
use Light\ObjectAccess\Type\CollectionType;
use Light\ObjectAccess\Type\CollectionTypeHelper;

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

	/**
	 * Returns the address of this resource.
	 * @return ResourceAddress
	 */
	public function getAddress();

	/**
	 * @return Origin
	 */
	public function getOrigin();
}