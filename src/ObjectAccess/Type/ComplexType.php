<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Exceptions\TypeException;
use Light\ObjectAccess\Transaction\Transaction;
use Light\ObjectAccess\Type\Complex\Property;

interface ComplexType extends Type
{
	/**
	 * Returns a description for the named property.
	 * @param string	$propertyName
	 * @throws TypeException	If the property doesn't exist.
	 * @return Property
	 */
	public function getProperty($propertyName);

	/**
	 * Returns a list of all properties defined in this type.
	 * @return Property[]
	 */
	public function getProperties();

	/**
	 * Returns the PHP class name of the objects supported by this complex type.
	 * @return string
	 */
	public function getClassName();
}