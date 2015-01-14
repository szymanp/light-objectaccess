<?php
namespace Light\ObjectAccess\Type;

use Light\Exception\NotImplementedException;
use Light\ObjectAccess\Query\Scope;
use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;
use Light\ObjectAccess\Resource\ResolvedValue;
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
}