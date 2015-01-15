<?php
namespace Light\ObjectAccess\Type;

use Light\Exception\NotImplementedException;
use Light\ObjectAccess\Exception\ResourceException;
use Light\ObjectAccess\Exception\TypeException;
use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;
use Light\ObjectAccess\Resource\ResolvedObject;
use Light\ObjectAccess\Resource\ResolvedResource;
use Light\ObjectAccess\Resource\ResolvedValue;
use Light\ObjectAccess\Transaction\Transaction;
use Light\ObjectAccess\Type\Complex\Value_Concrete;
use Light\ObjectAccess\Type\Complex\Value_Unavailable;

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
	 * @return ResolvedResource	the property value
	 */
	public function readProperty(ResolvedObject $resource, $propertyName)
	{
		$property = $this->getType()->getProperty($propertyName);

		if (!$property->isReadable())
		{
			throw new TypeException("Property %1::%2 is not readable", $this->getName(), $propertyName);
		}

		$value = $property->readProperty($resource);
		$origin = Origin::propertyOfObject($resource, $propertyName);
		$resourceAddress = $resource->getAddress()->appendElement($propertyName);

		if ($value instanceof Value_Concrete)
		{
			$resultType = $this->typeRegistry->getTypeHelperByName($property->getTypeName());

			return ResolvedValue::create($resultType, $value->getValue(), $resourceAddress, $origin);
		}
		elseif ($value instanceof Value_Unavailable)
		{
			$resultTypeName = $value->getTypeName() ?: $property->getTypeName();
			if (is_null($resultTypeName))
			{
				throw new ResourceException("Could not determine type for unavailable value of property %1::%2", $this->getName(), $propertyName);
			}
			$resultType = $this->typeRegistry->getTypeHelperByName($resultTypeName);

			if ($resultType->getType() instanceof CollectionType)
			{
				return new ResolvedCollectionResource($resultType, $resourceAddress, $origin);
			}
			else
			{
				// We only support unavailable values for collections.
				throw new NotImplementedException();
			}
		}

		throw new \LogicException();
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

	/**
	 * Returns true if the given value is valid for this type.
	 * @param mixed $value
	 * @return boolean
	 */
	public function isValidValue($value)
	{
		return $this->getType()->isValidValue($value);
	}
}