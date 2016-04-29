<?php
namespace Light\ObjectAccess\Type;

use Szyman\Exception\NotImplementedException;
use Light\ObjectAccess\Exception\ResourceException;
use Light\ObjectAccess\Exception\TypeException;
use Light\ObjectAccess\Exception\PropertyException;
use Light\ObjectAccess\Exception\TypeCapabilityException;
use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;
use Light\ObjectAccess\Resource\ResolvedNull;
use Light\ObjectAccess\Resource\ResolvedObject;
use Light\ObjectAccess\Resource\ResolvedResource;
use Light\ObjectAccess\Resource\ResolvedValue;
use Light\ObjectAccess\Resource\Addressing\CanonicalResourceAddress;
use Light\ObjectAccess\Resource\Util\EmptyResourceAddress;
use Light\ObjectAccess\Transaction\Transaction;
use Light\ObjectAccess\Type\Complex\CanonicalAddress;
use Light\ObjectAccess\Type\Complex\Create;
use Light\ObjectAccess\Type\Complex\Value_Concrete;
use Light\ObjectAccess\Type\Complex\Value_NotExists;
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
	 * @return TypeHelper
	 */
	public function getPropertyTypeHelper($propertyName)
	{
		$typeName = $this->getType()->getProperty($propertyName)->getTypeName();
		return $this->typeRegistry->getTypeHelperByName($typeName);
	}

	/**
	 * Creates a new resource of this type.
	 * @param Transaction $transaction
	 * @return ResolvedObject	Returns a resource object with an empty address and no origin.
	 * @throws TypeCapabilityException If the type does not support creation of new resources.
	 */
	public function createResource(Transaction $transaction)
	{
		$type = $this->getType();
		if ($type instanceof Create)
		{
			$object = $type->createObject($transaction);
			$address = ($type instanceof CanonicalAddress) ? CanonicalResourceAddress::create($type->getCanonicalAddress($object)) : EmptyResourceAddress::create();

			$resource = new ResolvedObject($this, $object, $address, Origin::unavailable());
			$transaction->markAsCreated($resource);
			return $resource;
		}
		else
		{
			throw new TypeCapabilityException($this, Create::class, 'Type does not support creation of objects');
		}
	}
	
	/**
	 * Clears all fields on the resource.
	 *
	 * 
	 *
	 * @param ResolvedObject $resource
	 * @param Transaction    $transaction
	 */
	public function clearResource(ResolvedObject $resource, Transaction $transaction)
	{
		
	}

	public function deleteResource(Transaction $transaction)
	{
		// TODO
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
			throw new PropertyException($this, $property, "is not readable");
		}

		$value = $property->readProperty($resource);
		$origin = Origin::propertyOfObject($resource, $propertyName);
		$resourceAddress = $resource->getAddress()->appendElement($propertyName);

		if ($value instanceof Value_Concrete)
		{
			$resultTypeName = $property->getTypeName();
			if (is_null($resultTypeName))
			{
				throw new TypeException("Property %1::%2 does not have type information", $this->getName(), $propertyName);
			}
			$resultType = $this->typeRegistry->getTypeHelperByName($resultTypeName);

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
				throw new NotImplementedException("Unavailable values are currently only supported for collections");
			}
		}
		elseif ($value instanceof Value_NotExists)
		{
			$resultTypeName = $property->getTypeName();
			if (is_null($resultTypeName))
			{
				throw new TypeException("Property %1::%2 does not have type information", $this->getName(), $propertyName);
			}
			$resultType = $this->typeRegistry->getTypeHelperByName($resultTypeName);

			return new ResolvedNull($resultType, $resourceAddress, $origin);
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
			throw new PropertyException($this, $property, 'is not writable');
		}

		$property->writeProperty($resource, $value, $transaction);
		$transaction->markAsChanged($resource);
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

	/**
	 * Returns a {@link ResolvedObject} object for the given value.
	 *
	 * The value is resolved with no address and no origin.
	 *
	 * @param object	$value
	 * @return ResolvedObject
	 * @throws ResourceException
	 */
	public function resolveValue($value)
	{
		if ($this->isValidValue($value))
		{
			return new ResolvedObject($this, $value, EmptyResourceAddress::create(), Origin::unavailable());
		}
		else
		{
			throw new ResourceException("Value is not compatible with type %1", $this->getName());
		}
	}
}