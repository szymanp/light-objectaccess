<?php
namespace Light\ObjectAccess\Type;

use Light\Exception\InvalidReturnValue;
use Light\Exception\NotImplementedException;
use Light\ObjectAccess\Exception\TypeException;
use Light\ObjectAccess\Query\Scope;
use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;
use Light\ObjectAccess\Resource\ResolvedValue;
use Light\ObjectAccess\Resource\Util\ResourceWrappingIterator;
use Light\ObjectAccess\Transaction\Transaction;
use Light\ObjectAccess\Type\Collection\Append;
use Light\ObjectAccess\Type\Collection\Iterate;
use Light\ObjectAccess\Type\Complex\Value_Concrete;
use Light\ObjectAccess\Type\Complex\Value_Unavailable;

class CollectionTypeHelper extends TypeHelper
{
	/**
	 * @return CollectionType
	 */
	public function getType()
	{
		return parent::getType();
	}

	/**
	 * Returns the helper for the base type for this collection.
	 * @return TypeHelper
	 * @throws \Light\ObjectAccess\Exception\TypeException
	 */
	public function getBaseTypeHelper()
	{
		return $this->typeRegistry->getTypeHelperByName($this->getType()->getBaseTypeName());
	}

	/**
	 * Returns true if the given value is valid for this type.
	 * @param mixed $value
	 * @return boolean
	 */
	public function isValidValue($value)
	{
		return $this->getType()->isValidValue($this->typeRegistry, $value);
	}

	/**
	 * Returns an element from the collection at the given key.
	 * @param ResolvedCollection $coll
	 * @param string|integer    $key
	 * @return ResolvedValue	A ResolvedValue object, if the element exists;
	 *                       	otherwise, NULL.
	 */
	public function getElementAtKey(ResolvedCollection $coll, $key)
	{
		$elementObject = $this->getType()->getElementAtKey($coll, $key);

		if ($elementObject->exists())
		{
			$origin = Origin::elementInCollection($coll, $key);
			$resourceAddress = $coll->getAddress()->appendScope(Scope::createWithKey($key));

			$valueObject = $elementObject->getValue();
			if ($valueObject instanceof Value_Concrete)
			{
				return ResolvedValue::create($this->getBaseTypeHelper(), $valueObject->getValue(), $resourceAddress, $origin);
			}
			else if ($valueObject instanceof Value_Unavailable)
			{
				$baseTypeHelper = $this->getBaseTypeHelper();
				if ($baseTypeHelper instanceof CollectionTypeHelper)
				{
					return new ResolvedCollectionResource($baseTypeHelper, $resourceAddress, $origin);
				}
				else
				{
					// We only support unavailable values for collections.
					throw new NotImplementedException();
				}
			}
			else
			{
				throw new \LogicException();
			}
		}
		else
		{
			// The element does not exist.
			return null;
		}
	}

	/**
	 * Appends a value to the collection.
	 * @param ResolvedCollection $collection
	 * @param mixed              $value
	 * @param Transaction        $transaction
	 * @throws TypeException	 If the type does not support appending.
	 */
	public function appendValue(ResolvedCollection $collection, $value, Transaction $transaction)
	{
		if ($this->type instanceof Append)
		{
			$this->type->appendValue($collection, $value, $transaction);
		}
		else
		{
			throw new TypeException("Type %1 does not support appending", $this->getName());
		}
	}

	/**
	 * Returns an Iterator over the elements in the given collection.
	 * @param ResolvedCollection $collection
	 * @return \Iterator
	 * @throws TypeException	If the type does not support iteration.
	 */
	public function getIterator(ResolvedCollection $collection)
	{
		if ($this->type instanceof Iterate)
		{
			$iterator = $this->type->getIterator($collection);
			if (!($iterator instanceof \Iterator))
			{
				throw new InvalidReturnValue($this->type, "getIterator", $iterator, "Iterator");
			}
			return new ResourceWrappingIterator($collection, $iterator);
		}
		else
		{
			throw new TypeException("Type %1 does not support iteration", $this->getName());
		}
	}
}