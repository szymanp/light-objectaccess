<?php
namespace Light\ObjectAccess\Type\Util;

use Light\ObjectAccess\Exception\TypeException;
use Light\ObjectAccess\Type\Complex\Property;
use Light\ObjectAccess\Type\ComplexType;
use Light\ObjectAccess\Type\Exception;

class DefaultComplexType implements ComplexType
{
	/** @var string */
	private $className;
	/** @var array<string, Property> */
	private $properties = array();

	public function __construct($className)
	{
		$this->className = $className;
	}

	/**
	 * Returns a description of the named property.
	 * @param string $propertyName
	 * @throws TypeException    If the property doesn't exist.
	 * @return Property
	 */
	public function getProperty($propertyName)
	{
		if (isset($this->properties[$propertyName]))
		{
			return $this->properties[$propertyName];
		}
		throw new TypeException("Property %1 does not exist", $propertyName);
	}

	/**
	 * Returns a list of all properties defined in this type.
	 * @return Property[]
	 */
	public function getProperties()
	{
		return array_values($this->properties);
	}

	/**
	 * Returns the PHP class name of the objects supported by this complex type.
	 * @return string
	 */
	public function getClassName()
	{
		return $this->className;
	}

	/**
	 * Returns true if the given value can be handled by this type.
	 * @param mixed $value
	 * @return boolean
	 */
	public function isValidValue($value)
	{
		return is_object($value)
		       && ($value instanceof $this->className);
	}

	/**
	 * Adds a new property to this complex type.
	 * @param Property $property
	 * @return $this
	 */
	public function addProperty(Property $property)
	{
		$this->properties[$property->getName()] = $property;
		return $this;
	}
}