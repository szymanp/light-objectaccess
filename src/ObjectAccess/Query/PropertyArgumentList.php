<?php
namespace Light\ObjectAccess\Query;

use Light\ObjectAccess\Exception\PropertyException;
use Light\ObjectAccess\Query\Argument\QueryArgument;
use Light\ObjectAccess\Type\Collection\Property;
use Light\ObjectAccess\Type\TypeHelper;

class PropertyArgumentList implements \IteratorAggregate
{
	/** @var Property */
	private $property;
	/** @var TypeHelper */
	private $typeHelper;
	/** @var QueryArgument[] */
	private $values = array();

	public function __construct(TypeHelper $typeHelper, Property $property)
	{
		$this->property = $property;
		$this->typeHelper = $typeHelper;
	}

	/**
	 * Returns true if there are no values in this list.
	 * @return bool
	 */
	public function isEmpty()
	{
		return empty($this->values);
	}

	/**
	 * Appends a new argument value.
	 * @param QueryArgument $argument
	 * @return $this
	 * @throws PropertyException	If the argument value is not valid for this property.
	 */
	public function append(QueryArgument $argument)
	{
		if (!$this->typeHelper->isValidValue($argument->getValue()))
		{
			throw new PropertyException($this->typeHelper, $this->property, ' does not support values of type ' . gettype($argument->getValue()));
		}

		$this->values[] = $argument;
		return $this;
	}

	/**
	 * Returns a list of values in this list.
	 * @return \Iterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->values);
	}

	/**
	 * Returns the property name.
	 * @return string
	 */
	public function getName()
	{
		return $this->property->getName();
	}

	/**
	 * @return Property
	 */
	public function getProperty()
	{
		return $this->property;
	}

	/**
	 * @return TypeHelper
	 */
	public function getTypeHelper()
	{
		return $this->typeHelper;
	}
}