<?php
namespace Light\ObjectAccess\Type;

use Light\Exception\InvalidReturnValue;
use Light\Exception\NotImplementedException;
use Light\ObjectAccess\Exception\TypeException;
use Light\ObjectAccess\Query\Scope;
use Light\ObjectAccess\Query\Scope\EmptyScope;
use Light\ObjectAccess\Query\Scope\KeyScope;
use Light\ObjectAccess\Query\Scope\Scope_Query;
use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;
use Light\ObjectAccess\Resource\ResolvedValue;
use Light\ObjectAccess\Resource\Util\ResourceWrappingIterator;
use Light\ObjectAccess\Transaction\Transaction;
use Light\ObjectAccess\Type\Collection\Append;
use Light\ObjectAccess\Type\Collection\Iterate;
use Light\ObjectAccess\Type\Collection\Search;
use Light\ObjectAccess\Type\Collection\SearchContext;
use Light\ObjectAccess\Type\Complex\Value_Concrete;
use Light\ObjectAccess\Type\Complex\Value_Unavailable;
use Light\ObjectAccess\Type\Util\EmptySearchContext;

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
	 * Returns a helper for the type of the given search property.
	 * @param string $propertyName
	 * @return TypeHelper
	 * @throws TypeException	If the underlying type does not support searching.
	 */
	public function getSearchPropertyTypeHelper($propertyName)
	{
		if ($this->type instanceof Search)
		{
			$property = $this->type->getProperty($propertyName);
			if (is_null($property))
			{
				throw new TypeException("Property \"%1\" does not exist in type %2", $propertyName, $this->getName());
			}
			return $this->typeRegistry->getTypeHelperByName($property->getTypeName());
		}
		else
		{
			throw new TypeException("Type %1 does not support search properties", $this->getName());
		}
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
	 * Returns an iterator over collection elements that match the given scope.
	 *
	 * Note that this is an utility method. There are more specialized methods
	 * either on the {@link CollectionTypeHelper} or on the {@link CollectionType} itself
	 * if one knows what type of Scope is involved.
	 * The specialized methods may accept more parameters and return a more precise value
	 * (e.g. a single value instead of an iterator).
	 *
	 * @param ResolvedCollection $collection
	 * @param Scope              $scope
	 * @return \Iterator
	 * @throws NotImplementedException
	 * @throws TypeException
	 */
	public function getElements(ResolvedCollection $collection, Scope $scope)
	{
		if ($scope instanceof KeyScope)
		{
			$result = $this->getElementAtKey($collection, $scope->getKey());
			return new \ArrayIterator(array($scope->getKey() => $result));
		}
		elseif ($scope instanceof EmptyScope)
		{
			return $this->getIterator($collection);
		}
		elseif ($scope instanceof Scope\QueryScope)
		{
			return $this->findElements($collection, $scope, EmptySearchContext::create());
		}
		// TODO	Implement for other Scope types.
		throw new NotImplementedException();
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
	 * Returns all objects matching the scope.
	 * @param ResolvedCollection 	$collection
	 * @param Scope\QueryScope		$scope
	 * @param SearchContext			$context
	 * @return \Iterator	An iterator over all objects matching the scope.
	 *                   	The key of the iterator should indicate the key of the object in the collection.
	 * @throws TypeException		If the type does not support searching.
	 */
	public function findElements(ResolvedCollection $collection, Scope\QueryScope $scope, SearchContext $context)
	{
		if ($this->type instanceof Search)
		{
			$iterator = $this->type->find($collection, $scope, $context);
			if (!($iterator instanceof \Iterator))
			{
				throw new InvalidReturnValue($this->type, "find", $iterator, "Iterator");
			}
			return new ResourceWrappingIterator($collection, $iterator);
		}
		else
		{
			throw new TypeException("Type %1 does not support searching", $this->getName());
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