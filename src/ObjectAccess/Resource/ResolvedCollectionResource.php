<?php
namespace Light\ObjectAccess\Resource;

use Light\ObjectAccess\Resource\Addressing\ResourceAddress;
use Light\ObjectAccess\Type\CollectionTypeHelper;

final class ResolvedCollectionResource extends ResolvedResource implements ResolvedCollection
{
	public function __construct(CollectionTypeHelper $typeHelper, ResourceAddress $address, Origin $origin)
	{
		parent::__construct($typeHelper, $address, $origin);
	}
}
