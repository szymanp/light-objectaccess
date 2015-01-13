<?php
namespace Light\ObjectAccess\Type\Util;

use Light\Exception\Exception;
use Light\ObjectAccess\Exception\ResourceException;
use Light\ObjectAccess\Type\Collection\Element;
use Light\ObjectAccess\Type\CollectionType;
use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Type\TypeRegistry;

/**
 *
 */
class DefaultCollectionType implements CollectionType
{
	/** @var string */
	private $baseTypeName;

	public function __construct($baseTypeName)
	{
		$this->baseTypeName = $baseTypeName;
	}

	/**
	 * Returns the type name of items in this collection.
	 * @return string
	 */
	public function getBaseTypeName()
	{
		return $this->baseTypeName;
	}

	// NOTE:
	// baseTypeName is unlike phpType in SimpleType and className in ComplexType
	// because the others can be resolved without involving the TypeRegistry.

	/**
	 * Returns an element from the given collection at the specified offset.
	 * @param ResolvedCollection $coll
	 * @param string|integer     $key
	 * @return Element
	 * @throws ResourceException	If the collection does not support reading element values.
	 */
	public function getElementAtOffset(ResolvedCollection $coll, $key)
	{
		// FIXME
		$value = $coll->getValue();

		if (is_array($value))
		{
			if (isset($value[$key]))
			{
				return Element::value($value[$key]);
			}
			else
			{
				return Element::notExists();
			}
		}
		else if ($value instanceof \ArrayAccess)
		{
			if ($value->offsetExists($key))
			{
				return Element::value($value->offsetGet($key));
			}
			else
			{
				return Element::notExists();
			}
		}
		throw new ResourceException("Value is neither an array nor an ArrayAccess object");
	}

	/**
	 * Returns true if the given value can be handled by this type.
	 * @param TypeRegistry $typeRegistry
	 * @param mixed $value
	 * @return boolean
	 */
	public function isValidValue(TypeRegistry $typeRegistry, $value)
	{
		if (is_array($value))
		{
			return count($value) == 0
			       || $typeRegistry->getTypeHelperByName($this->getBaseTypeName())->isValidValue(end($value));
		}
		elseif ($value instanceof \ArrayAccess && $value instanceof \Traversable)
		{
			foreach($value as $element)
			{
				return $typeRegistry->getTypeHelperByName($this->getBaseTypeName())->isValidValue($element);

				// We only do one iteration
				break;
			}
			return true;
		}
		return false;
	}
}