<?php
namespace Light\ObjectAccess\Resource;

use Light\ObjectAccess\Resource\Addressing\ResourceAddress;
use Light\ObjectAccess\Type\CollectionTypeHelper;

final class ResolvedCollectionValue extends ResolvedValue  implements ResolvedCollection
{
	public function __construct(CollectionTypeHelper $collectionTypeHelper, $value, ResourceAddress $address, Origin $origin)
	{
		parent::__construct($collectionTypeHelper, $value, $address, $origin);
	}
}