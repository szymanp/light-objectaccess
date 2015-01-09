<?php
namespace Light\ObjectAccess\Resource;

use Light\ObjectAccess\Resource\Addressing\ResourceAddress;
use Light\ObjectAccess\Type\CollectionTypeHelper;

final class ResolvedCollection extends ResolvedValue
{
	public function __construct(CollectionTypeHelper $collectionTypeHelper, $value, ResourceAddress $address, Origin $origin)
	{
		parent::__construct($collectionTypeHelper, $value, $address, $origin);
	}

	/**
	 * Returns the type of the value.
	 * @return CollectionType
	 */
	public function getType()
	{
		return parent::getType();
	}

	/**
	 * @return CollectionTypeHelper
	 */
	public function getTypeHelper()
	{
		return parent::getTypeHelper();
	}

}