<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Exception\TypeException;
use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\ResolvedObject;
use Light\ObjectAccess\Resource\ResolvedValue;
use Light\ObjectAccess\Transaction\Transaction;

class ComplexTypeHelper extends TypeHelper
{
	/**
	 * @return ComplexType
	 */
	public function getType()
	{
		return parent::getType();
	}

	/**
	 * Returns a Type for the specified property.
	 * @param string $propertyName
	 * @return Type
	 */
	public function getPropertyType($propertyName)
	{
		$typeName = $this->getType()->getProperty($propertyName)->getTypeName();
		return $this->typeRegistry->getTypeHelperByName($typeName)->getType();
	}

	public function createResource(Transaction $transaction)
	{

	}

	public function deleteResource(Transaction $transaction)
	{

	}

	/**
	 * Reads a value from the named property on the specified object.
	 *
	 * @param ResolvedObject	$resource
	 * @param string        	$propertyName
	 * @throws \Exception	If the property doesn't exist or cannot be read.
	 * @return ResolvedValue	the property value
	 */
	public function readProperty(ResolvedObject $resource, $propertyName)
	{
		$property = $this->getType()->getProperty($propertyName);

		if (!$property->isReadable())
		{
			throw new TypeException("Property %1::%2 is not readable", $this->getName(), $propertyName);
		}

		$value = $property->readProperty($resource);

		$resultType = $this->typeRegistry->getTypeHelperByName($property->getTypeName());
		$origin = Origin::propertyOfObject($resource, $propertyName);
		$resourceAddress = $resource->getAddress()->appendElement($propertyName);

		return ResolvedValue::create($resultType, $value, $resourceAddress, $origin);
	}

	/**
	 * Sets the value for the named property on the specified object.
	 *
	 * @param ResolvedObject    $resource
	 * @param string        	$propertyName
	 * @param mixed        	 	$value
	 * @param Transaction  		$transaction
	 * @throws \Exception	If the property doesn't exist or cannot be written to.
	 */
	public function writeProperty(ResolvedObject $resource, $propertyName, $value, Transaction $transaction)
	{
		$property = $this->getType()->getProperty($propertyName);

		if (!$property->isWritable())
		{
			throw new TypeException("Property %1::%2 is not writable", $this->getName(), $propertyName);
		}

		$property->writeProperty($resource, $value, $transaction);
	}
}