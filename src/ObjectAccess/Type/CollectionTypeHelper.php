<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Type\Collection\SetElementAtKey;
use Szyman\Exception\UnexpectedValueException;
use Szyman\Exception\NotImplementedException;
use Light\ObjectAccess\Exception\TypeException;
use Light\ObjectAccess\Exception\TypeCapabilityException;
use Light\ObjectAccess\Query\Scope;
use Light\ObjectAccess\Query\Scope\EmptyScope;
use Light\ObjectAccess\Query\Scope\KeyScope;
use Light\ObjectAccess\Query\Scope\Scope_Query;
use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;
use Light\ObjectAccess\Resource\ResolvedCollectionValue;
use Light\ObjectAccess\Resource\ResolvedResource;
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
	 * @throws TypeCapabilityException If the underlying type does not support searching.
	 * @throws TypeException
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
			throw new TypeCapabilityException($this, Search::class, 'Type does not support search properties');
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
	 * Returns an element from the collection at the given key.
	 * @param ResolvedCollection $coll
	 * @param string|integer     $key
	 * @return ResolvedValue 	A ResolvedValue object, if the element exists;
	 *                        	otherwise, NULL.
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
	 * Returns a collection with values matching the scope from the collection resource.
	 * @param ResolvedCollectionResource 	$collection
	 * @param Scope\QueryScope				$scope
	 * @param SearchContext					$context
	 * @return ResolvedCollectionValue	A collection that contains all elements matching the scope.
	 *                                 	This collection has the same origin as the input collection,
	 *                                	and an address with the scope appended.
	 * @throws TypeCapabilityException	If the type does not support searching.
	 */
	public function queryCollection(ResolvedCollectionResource $collection, Scope\QueryScope $scope, SearchContext $context)
	{
		if ($this->type instanceof Search)
		{
			$collectionValue = $this->type->find($collection, $scope, $context);
			if (!$this->isValidValue($collectionValue))
			{
				throw UnexpectedValueException::newInvalidReturnValue($this->type, "find", $collectionValue, "Not a valid value for this type");
			}
			$address = $collection->getAddress()->appendScope($scope);
			return new ResolvedCollectionValue($this, $collectionValue, $address, $collection->getOrigin());
		}
		else
		{
			throw new TypeCapabilityException($this, Search::class, 'Type does not support searching');
		}
	}

	/**
	 * Returns a collection with all values from the collection resource.
	 * @param ResolvedCollectionResource $collection
	 * @return ResolvedCollectionValue	A collection that contains all values from the resource.
	 *                                 	This collection has the same origin as the input collection,
	 *                                	and an address with the scope appended.
	 * @throws TypeCapabilityException	If the type does not support iteration.
	 */
	public function readCollection(ResolvedCollectionResource $collection)
	{
		if ($this->type instanceof Iterate)
		{
			$collectionValue = $this->type->read($collection);
			if (!$this->isValidValue($collectionValue))
			{
				throw new InvalidReturnValue(get_class($this->type), "read", $collectionValue, "A value valid for this type");
			}
			$address = $collection->getAddress()->appendScope(Scope::createEmptyScope());
			return new ResolvedCollectionValue($this, $collectionValue, $address, $collection->getOrigin());
		}
		else
		{
			throw new TypeCapabilityException($this, Iterate::class, 'Type does not support iteration');
		}
	}

	/**
	 * Appends a value to the collection.
	 * @param ResolvedCollection $collection
	 * @param mixed              $value
	 * @param Transaction        $transaction
	 * @throws TypeCapabilityException	If the type does not support appending.
	 */
	public function appendValue(ResolvedCollection $collection, $value, Transaction $transaction)
	{
		if ($this->type instanceof Append)
		{
			$this->type->appendValue($collection, $value, $transaction);
			$transaction->markAsChanged($collection);
		}
		else
		{
			throw new TypeCapabilityException($this, Append::class, 'Type does not support appending');
		}
	}

	/**
	 * Sets a value in the collection at the specified key.
	 * @param ResolvedCollection $collection
	 * @param mixed              $key
	 * @param mixed              $value
	 * @param Transaction        $transaction
	 * @throws TypeCapabilityException	If the type does not support setting elements by key.
	 */
	public function setValue(ResolvedCollection $collection, $key, $value, Transaction $transaction)
	{
		if ($this->type instanceof SetElementAtKey)
		{
			$this->type->setElementAtKey($collection, $key, $value, $transaction);
			$transaction->markAsChanged($collection);
		}
		else
		{
			throw new TypeCapabilityException($this, SetElementAtKey::class, 'Type does not support setting values with a key');
		}
	}

	/**
	 * Returns an iterator over the elements in the given collection.
	 * @param ResolvedCollection $collection
	 * @return \Iterator
	 * @throws TypeCapabilityException If the type does not support iteration.
	 */
	public function getIterator(ResolvedCollection $collection)
	{
		if ($this->type instanceof Iterate)
		{
			if ($collection instanceof ResolvedCollectionResource)
			{
				$collectionValue = $this->readCollection($collection);
			}
			else
			{
				$collectionValue = $collection;
			}

			$iterator = $this->type->getIterator($collectionValue);
			if (!($iterator instanceof \Iterator))
			{
				throw new InvalidReturnValue($this->type, "getIterator", $iterator, "Iterator");
			}
			return new ResourceWrappingIterator($collection, $iterator);
		}
		else
		{
			throw new TypeCapabilityException($this, Iterate::class, 'Type does not support iteration');
		}
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
	 * This method can only be called with a collection that does not have resolved values.
	 *
	 * @param ResolvedCollectionResource 	$collection
	 * @param Scope              			$scope
	 * @return \Iterator
	 * @throws NotImplementedException
	 * @throws TypeCapabilityException
	 */
	public function getIteratorWithScope(ResolvedCollectionResource $collection, Scope $scope)
	{
		if ($scope instanceof KeyScope)
		{
			$result = $this->getElementAtKey($collection, $scope->getKey());
			return new \ArrayIterator(array($scope->getKey() => $result));
		}
		elseif ($scope instanceof EmptyScope)
		{
			return $this->getIterator($this->readCollection($collection));
		}
		elseif ($scope instanceof Scope\QueryScope)
		{
			return $this->getIterator($this->queryCollection($collection, $scope, EmptySearchContext::create()));
		}
		// TODO	Implement for other Scope types.
		throw new NotImplementedException();
	}

	/**
	 * Applies the scope to the collection resource.
	 *
	 * This method returns either a single element from the collection (if this type of scope uniquely identifies
	 * an element), or returns a collection with values that match the scope.
	 *
	 * @param ResolvedCollectionResource $collection
	 * @param Scope                      $scope
	 * @return ResolvedResource	A single element or a {@link ResolvedCollectionValue} with values matching the scope.
	 */
	public function applyScope(ResolvedCollectionResource $collection, Scope $scope)
	{
		if ($scope instanceof KeyScope)
		{
			return $this->getElementAtKey($collection, $scope->getKey());
		}
		elseif ($scope instanceof EmptyScope)
		{
			return $this->readCollection($collection);
		}
		elseif ($scope instanceof Scope\QueryScope)
		{
			return $this->queryCollection($collection, $scope, EmptySearchContext::create());
		}
		// TODO	Implement for other Scope types.
		throw new NotImplementedException();
	}
}