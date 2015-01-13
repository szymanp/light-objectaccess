<?php
namespace Light\ObjectAccess\Type\Util;

use Light\Exception\Exception;
use Light\Exception\InvalidParameterValue;
use Light\Exception\NotImplementedException;
use Light\ObjectAccess\Type\Type;
use Light\ObjectAccess\Type\TypeProvider;

class DefaultTypeProvider implements TypeProvider
{
	/** @var NameProvider */
	private $nameProvider;

	private $types = array();
	private $names = array();
	private $addrs = array();

	public function __construct()
	{
		$this->nameProvider = new DefaultNameProvider();
	}

	/**
	 * Register a new type with this provider.
	 * @param Type $type
	 * @param string $name
	 * @param string $address
	 * @return $this
	 */
	public function addType(Type $type, $name = null, $address = null)
	{
		if (in_array($type, $this->types, true))
		{
			throw new InvalidParameterValue('$type', $type, "Type is already registered with this provider");
		}

		if (is_null($name))
		{
			$name = $this->nameProvider->getTypeName($type);
		}
		if (is_null($address))
		{
			$address = $this->nameProvider->getTypeUri($type);
		}

		$this->types[] = $type;
		$this->names[$name] = $type;
		$this->addrs[$address] = $type;

		return $this;
	}

	/**
	 * Returns the URI for the given type.
	 * @param Type $type
	 * @return string    An URI for the specified type.
	 */
	public function getTypeUri(Type $type)
	{
		$key = array_search($type, $this->addrs, true);
		if ($key)
		{
			return $key;
		}
		throw new Exception("Type is not registered with this provider");
	}

	/**
	 * Returns the name for the given type.
	 * @param Type $type
	 * @return string    A name for the given type.
	 *                     Names for collection types must end in [].
	 */
	public function getTypeName(Type $type)
	{
		$key = array_search($type, $this->names, true);
		if ($key)
		{
			return $key;
		}
		throw new Exception("Type is not registered with this provider");
	}

	/**
	 * Returns a Type object appropriate for the given value.
	 * @param mixed $value
	 * @return Type    A Type object appropriate for the value, if available; otherwise, NULL.
	 */
	public function getTypeByValue($value)
	{
		if (is_scalar($value))
		{
			return $this->getTypeByName(gettype($value));
		}

		foreach($this->types as $type)
		{
			if ($type->isValidValue($value))
			{
				return $type;
			}
		}
		return null;
	}

	/**
	 * Returns a Type corresponding to the given name.
	 * @param string $typeName A name of the type. For example, string[].
	 * @return Type A Type object corresponding to the type name, if available; otherwise, NULL.
	 */
	public function getTypeByName($typeName)
	{
		if (isset($this->names[$typeName]))
		{
			return $this->names[$typeName];
		}

		if (BuiltinSimpleType::isBuiltinType($typeName))
		{
			$type = new BuiltinSimpleType($typeName);
			$this->addType($type);
			return $type;
		}

		return null;
	}
}