<?php
namespace Light\ObjectAccess\Resource;

use Light\ObjectAccess\Resource\Addressing\ResourceAddress;
use Light\ObjectAccess\Type\Type;
use Light\ObjectAccess\Type\TypeHelper;

/**
 * A resource resolved from a resource path.
 *
 * This class holds the type, address and origin of a resource that was obtained from a property
 * or element of another resource, or created directly from an existing object.
 *
 * Note that subclasses of this class are not required to hold a value for the resource as some resources
 * might not have a value that can be represented in a PHP variable.
 */
abstract class ResolvedResource
{
	/** @var TypeHelper */
	protected $typeHelper;

	/** @var ResourceAddress */
	protected $address;

	/** @var Origin */
	protected $origin;

	public function __construct(TypeHelper $typeHelper, ResourceAddress $address, Origin $origin)
	{
		$this->typeHelper = $typeHelper;
		$this->address = $address;
		$this->origin = $origin;
	}

	/**
	 * Returns the type of the value.
	 * @return Type
	 */
	public function getType()
	{
		return $this->typeHelper->getType();
	}

	/**
	 * Returns the TypeHelper for the value.
	 * @return TypeHelper
	 */
	public function getTypeHelper()
	{
		return $this->typeHelper;
	}

	/**
	 * Returns the address of this resource.
	 * @return ResourceAddress
	 */
	public function getAddress()
	{
		return $this->address;
	}

	/**
	 * @return Origin
	 */
	public function getOrigin()
	{
		return $this->origin;
	}
}