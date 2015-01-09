<?php
namespace Light\ObjectAccess\Resource;

use Light\ObjectAccess\Resource\Addressing\ResourceAddress;
use Light\ObjectAccess\Type\ComplexTypeHelper;

final class ResolvedObject extends ResolvedValue
{
	public function __construct(ComplexTypeHelper $typeHelper, $value, ResourceAddress $address, Origin $origin)
	{
		parent::__construct($typeHelper, $value, $address, $origin);
	}

	/**
	 * @return ComplexType
	 */
	public function getType()
	{
		return parent::getType();
	}

	/**
	 * @return ComplexTypeHelper
	 */
	public function getTypeHelper()
	{
		return parent::getTypeHelper();
	}

}
