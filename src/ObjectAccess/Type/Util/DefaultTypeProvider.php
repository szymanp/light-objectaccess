<?php
namespace Light\ObjectAccess\Type\Util;

use Szyman\Exception\Exception;
use Light\ObjectAccess\Type\CollectionType;
use Light\ObjectAccess\Type\ComplexType;
use Light\ObjectAccess\Type\SimpleType;
use Light\ObjectAccess\Type\Type;
use Light\ObjectAccess\Type\TypeProvider;

class DefaultTypeProvider implements TypeProvider
{
	/** @var DefaultNameProvider */
	private $nameProvider;

	private $types = array();
	private $names = array();
	private $addrs = array();

	public function __construct()
	{
		$this->nameProvider = new DefaultNameProvider();
	}

	/**
	 * Sets the class prefix that will be stripped from class names.
	 *
	 * For example, if the prefix is set to "Application\Model", then the name for the class
	 * "Application\Model\User" would be "User".
	 *
	 * @param string $prefix
	 */
	public function setNamePrefix($prefix)
	{
		$this->nameProvider->setPrefix($prefix);
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
			throw new Exception("Type %1 is already registered with this provider", $type);
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
			if ($type instanceof CollectionType)
			{
				if ($type->isValidValue($this->getTypeName($type->getBaseTypeName()), $value))
				{
					return $type;
				}
			}
			elseif ($type instanceof ComplexType || $type instanceof SimpleType)
			{
				if ($type->isValidValue($value))
				{
					return $type;
				}
			}
			else
			{
				throw new \LogicException();
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

		// Here we assume that $typeName is equal to PHP's name for the type.
		// This is true as we are using DefaultNameProvider.
		if (BuiltinSimpleType::isBuiltinType($typeName))
		{
			$type = new BuiltinSimpleType($typeName);
			$this->addType($type);
			return $type;
		}

		return null;
	}

	/**
	 * Returns a Type corresponding to the given URI.
	 * @param string $typeUri An URI for a type.
	 * @return Type A Type object corresponding to the URI, if available; otherwise, NULL.
	 */
	public function getTypeByURI($typeUri)
	{
		$name = $this->nameProvider->getNameFromUri($typeUri);
		if (is_null($name))
		{
			return null;
		}
		else
		{
			return $this->getTypeByName($name);
		}
	}
}