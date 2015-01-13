<?php
namespace Light\ObjectAccess\Resource;

use Light\ObjectAccess\Resource\Addressing\ResourceAddress;
use Light\ObjectAccess\Type\CollectionTypeHelper;
use Light\ObjectAccess\Type\ComplexTypeHelper;
use Light\ObjectAccess\Type\SimpleTypeHelper;
use Light\ObjectAccess\Type\TypeHelper;

/**
 * A value read from a resource path.
 */
abstract class ResolvedValue extends ResolvedResource
{
	/** @var mixed */
	protected $value;

	/**
	 * Creates a new ResolvedValue object appropriate for the given type.
	 * @param TypeHelper      $typeHelper
	 * @param mixed           $value
	 * @param ResourceAddress $address
	 * @param Origin          $origin
	 * @return ResolvedCollectionValue|ResolvedObject|ResolvedScalar
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
			return new ResolvedCollectionValue($typeHelper, $value, $address, $origin);
		}
		throw new \LogicException();
	}

	public function __construct(TypeHelper $typeHelper, $value, ResourceAddress $address, Origin $origin)
	{
		parent::__construct($typeHelper, $address, $origin);
		$this->value = $value;
	}

	/**
	 * Returns the value.
	 */
	public function getValue()
	{
		return $this->value;
	}
}