<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Exception\TypeException;
use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\ResolvedValue;
use Light\ObjectAccess\Resource\Util\EmptyResourceAddress;

class TypeRegistry
{
	/** @var TypeProvider */
	private $typeProvider;

	/** @var array<string, TypeHelper> */
	private $typeHelpers = array();

	public function __construct(TypeProvider $typeProvider)
	{
		$this->typeProvider = $typeProvider;
	}

	/**
	 * @return TypeProvider
	 */
	public function getTypeProvider()
	{
		return $this->typeProvider;
	}

	/**
	 * Returns a TypeHelper object appropriate for the given value.
	 * @param mixed $value
	 * @return TypeHelper    A TypeHelper object appropriate for the value, if available; otherwise, NULL.
	 */
	public function getTypeHelperByValue($value)
	{
		$type = $this->typeProvider->getTypeByValue($value);
		if (is_null($type))
		{
			throw new TypeException("No type corresponding to value %1 is known by this TypeRegistry", $value);
		}
		return $this->getTypeHelperByType($type);
	}

	/**
	 * Returns a TypeHelper corresponding to the given name.
	 * @param string $typeName A name of the type. For example, string[].
	 * @return TypeHelper A Type object corresponding to the type name.
	 */
	public function getTypeHelperByName($typeName)
	{
		$type = $this->typeProvider->getTypeByName($typeName);
		if (is_null($type))
		{
			throw new TypeException("No type with name \"%1\" is known by this TypeRegistry", $typeName);
		}
		return $this->getTypeHelperByType($type);
	}

	/**
	 * Returns a TypeHelper for the given type.
	 * @param Type $type
	 * @return TypeHelper
	 */
	public function getTypeHelperByType(Type $type)
	{
		$hash = spl_object_hash($type);
		if (!isset($this->typeHelpers[$hash]))
		{
			$this->typeHelpers[$hash] = TypeHelper::create($this, $type);
		}
		return $this->typeHelpers[$hash];
	}

	/**
	 * Returns a name for the specified type.
	 * @param Type $type
	 * @return string
	 */
	public function getNameForType(Type $type)
	{
		return $this->typeProvider->getTypeName($type);
	}

	/**
	 * Returns an address string for the specified type.
	 * @param Type $type
	 * @return string
	 */
	public function getAddressForType(Type $type)
	{
		return $this->typeProvider->getTypeUri($type);
	}

	/**
	 * Returns a SimpleTypeHelper for the named type.
	 * @param string $name
	 * @return SimpleTypeHelper
	 * @throws TypeException
	 */
	public function getSimpleTypeHelper($name)
	{
		$helper = $this->getTypeHelperByName($name);
		if (!($helper instanceof SimpleTypeHelper))
		{
			throw new TypeException("Type \"%1\" does not resolve to a SimpleType", $name);
		}
		return $helper;
	}

	/**
	 * Returns a ComplexTypeHelper for the named type.
	 * @param string $name
	 * @return ComplexTypeHelper
	 * @throws TypeException
	 */
	public function getComplexTypeHelper($name)
	{
		$helper = $this->getTypeHelperByName($name);
		if (!($helper instanceof ComplexTypeHelper))
		{
			throw new TypeException("Type \"%1\" does not resolve to a ComplexType", $name);
		}
		return $helper;
	}

	/**
	 * Returns a CollectionTypeHelper for the named type.
	 * @param string $name
	 * @return CollectionTypeHelper
	 * @throws TypeException
	 */
	public function getCollectionTypeHelper($name)
	{
		$helper = $this->getTypeHelperByName($name);
		if (!($helper instanceof CollectionTypeHelper))
		{
			throw new TypeException("Type \"%1\" does not resolve to a CollectionType", $name);
		}
		return $helper;
	}

	/**
	 * Returns a {@link ResolvedValue} object for the given value.
	 *
	 * The value is resolved with no address and no origin.
	 *
	 * @param mixed	$value
	 * @return ResolvedValue
	 * @throws TypeException	If no type matching the given value is found.
	 */
	public function resolveValue($value)
	{
		$typeHelper = $this->getTypeHelperByValue($value);
		return ResolvedValue::create($typeHelper, $value, EmptyResourceAddress::create(), Origin::unavailable());
	}
}