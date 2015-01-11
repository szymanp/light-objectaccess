<?php
namespace Light\ObjectAccess\Resource;

use Light\ObjectAccess\Resource\Addressing\ResourceAddress;
use Light\ObjectAccess\Type\SimpleTypeHelper;

final class ResolvedScalar extends ResolvedValue
{
	public function __construct(SimpleTypeHelper $typeHelper, $value, ResourceAddress $address, Origin $origin)
	{
		parent::__construct($typeHelper, $value, $address, $origin);
	}
}