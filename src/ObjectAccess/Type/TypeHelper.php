<?php
namespace Light\ObjectAccess\Type;

abstract class TypeHelper
{
	/** @var Type */
	protected $type;

	/** @var TypeRegistry */
	protected $typeRegistry;

	/**
	 * Returns a TypeHelper instance for the given type.
	 * @param Type $type
	 * @return TypeHelper
	 */
	public static function create(TypeRegistry $typeRegistry, Type $type)
	{
		if ($type instanceof ComplexType)
		{
			return new ComplexTypeHelper($typeRegistry, $type);
		}
		elseif ($type instanceof SimpleType)
		{
			return new SimpleTypeHelper($typeRegistry, $type);
		}
		elseif ($type instanceof CollectionType)
		{
			return new CollectionTypeHelper($typeRegistry, $type);
		}
		throw new \LogicException("Unsupported subclass of Type");

	}

	public function __construct(TypeRegistry $typeRegistry, Type $type)
	{
		$this->type = $type;
		$this->typeRegistry = $typeRegistry;
	}

	/**
	 * @return Type
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Returns an address string for this type.
	 * @return string
	 */
	public function getAddress()
	{
		return $this->typeRegistry->getAddressForType($this->type);
	}

	/**
	 * Returns a name for this type.
	 * @return string
	 */
	public function getName()
	{
		return $this->typeRegistry->getNameForType($this->type);
	}
}