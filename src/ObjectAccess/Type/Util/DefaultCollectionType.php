<?php
namespace Light\ObjectAccess\Type\Util;

use Szyman\Exception\Exception;
use Szyman\Exception\UnexpectedValueException;
use Light\ObjectAccess\Exception\ResourceException;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;
use Light\ObjectAccess\Resource\ResolvedCollectionValue;
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

	/** @var \Closure */
	private $elementGetter;

	public function __construct($baseTypeName)
	{
		$this->baseTypeName = $baseTypeName;
	}

	/**
	 * Returns the type name of items in this collection.
	 * @internal	baseTypeName is unlike phpType in SimpleType and className in ComplexType
	 * 				because the others can be resolved without involving the TypeRegistry.
	 * @return string
	 */
	public function getBaseTypeName()
	{
		return $this->baseTypeName;
	}

	/**
	 * Returns an element from the given collection at the specified key.
	 * @param ResolvedCollection $coll
	 * @param string|integer     $key
	 * @return Element
	 * @throws ResourceException	If the collection does not support reading element values.
	 * @throws Exception
	 */
	public function getElementAtKey(ResolvedCollection $coll, $key)
	{
		if ($coll instanceof ResolvedCollectionResource)
		{
			$result = $this->getElementAtKeyFromResource($coll, $key);
			if (!($result instanceof Element))
			{
				throw UnexpectedValueException::newInvalidReturnValue("Closure", "", $result, "Expected Element object");
			}
			return $result;
		}
		elseif ($coll instanceof ResolvedCollectionValue)
		{
			// The value can be read directly from the resolved resource.
			$value = $coll->getValue();

			if (is_array($value))
			{
				if (isset($value[$key]))
				{
					return Element::valueOf($value[$key]);
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
					return Element::valueOf($value->offsetGet($key));
				}
				else
				{
					return Element::notExists();
				}
			}
			throw new ResourceException("Value is neither an array nor an ArrayAccess object");
		}
		throw new \LogicException();
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

	/**
	 * Sets the callback for reading elements from collections of this type.
	 * @param callable $elementGetter	A function of the form: function(ResolvedCollection $coll, $key) => Element.
	 */
	public function setElementGetter($elementGetter)
	{
		$this->elementGetter = $elementGetter;
	}

	/**
	 * Returns an element from the given collection resource at the specified key.
	 * @param ResolvedCollectionResource $coll
	 * @param string|integer     		 $key
	 * @return Element
	 * @throws Exception
	 */
	protected function getElementAtKeyFromResource(ResolvedCollectionResource $coll, $key)
	{
		// The resolved resource does not have any values.
		if (is_null($this->elementGetter))
		{
			throw new Exception("No element getter defined for this type");
		}

		return call_user_func($this->elementGetter, $coll, $key);
	}
}