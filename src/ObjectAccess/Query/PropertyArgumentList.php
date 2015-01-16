<?php
namespace Light\ObjectAccess\Query;

use Light\ObjectAccess\Exception\TypeException;
use Light\ObjectAccess\Query\Argument\QueryArgument;
use Light\ObjectAccess\Type\Collection\Property;
use Light\ObjectAccess\Type\TypeHelper;
use Traversable;

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
	 * @throws TypeException	If the argument value is not valid for this property.
	 */
	public function append(QueryArgument $argument)
	{
		if (!$this->typeHelper->isValidValue($argument->getValue()))
		{
			throw new TypeException("Value %1 is not valid for property %2::%3",
									$argument->getValue(),
									$this->getTypeHelper()->getName(),
									$this->getName());
		}

		$this->values[] = $argument;
		return $this;
	}

	/**
	 * Returns a list of values in this list.
	 * @return Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
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