<?php
namespace Light\ObjectAccess\Type;

use Light\Exception\NotImplementedException;

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
		throw new NotImplementedException;
	}

	/**
	 * Returns a TypeHelper corresponding to the given name.
	 * @param string $typeName A name of the type. For example, string[].
	 * @return TypeHelper A Type object corresponding to the type name.
	 */
	public function getTypeHelperByName($typeName)
	{
		$type = $this->typeProvider->getTypeByName($typeName);
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
}