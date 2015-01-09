<?php
namespace Light\ObjectAccess\Resource;

use Light\ObjectAccess\Resource\Addressing\ResourceAddress;
use Light\ObjectAccess\Type\CollectionTypeHelper;
use Light\ObjectAccess\Type\ComplexTypeHelper;
use Light\ObjectAccess\Type\TypeHelper;

/**
 * A value read from a resource path.
 */
abstract class ResolvedValue
{
	/** @var mixed */
	protected $value;
	
	/** @var TypeHelper */
	protected $typeHelper;

	/** @var ResourceAddress */
	protected $address;

	/** @var Origin */
	protected $origin;

	/**
	 * Creates a new ResolvedValue object appropriate for the given type.
	 * @param TypeHelper      $typeHelper
	 * @param mixed           $value
	 * @param ResourceAddress $address
	 * @param Origin          $origin
	 * @return ResolvedCollection|ResolvedObject|ResolvedScalar
	 */
	public static function create(TypeHelper $typeHelper, $value, ResourceAddress $address, Origin $origin)
	{
		if ($typeHelper instanceof ComplexTypeHelper)
		{
			return new ResolvedObject($typeHelper, $value, $address, $origin);
		}
		else if ($typeHelper instanceof SimpleTypeHelper)
		{
			return new ResolvedScalar($typeHelper, $value, $address, $origin);
		}
		else if ($typeHelper instanceof CollectionTypeHelper)
		{
			return new ResolvedCollection($typeHelper, $value, $address, $origin);
		}
		throw new \LogicException();
	}

	public function __construct(TypeHelper $typeHelper, $value, ResourceAddress $address, Origin $origin)
	{
		$this->value = $value;
		$this->typeHelper = $typeHelper;
		$this->address = $address;
		$this->origin = $origin;
	}

	/**
	 * Returns the value.
	 */
	public function getValue()
	{
		return $this->value;
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