<?php
namespace Light\ObjectAccess\Query;

use Light\ObjectAccess\Exception\TypeException;
use Light\ObjectAccess\Query\Argument\QueryArgument;
use Light\ObjectAccess\Type\Collection\Search;
use Light\ObjectAccess\Type\CollectionTypeHelper;

class QueryConcrete extends Query
{
	/** @var CollectionTypeHelper */
	private $typeHelper;
	/** @var Search */
	private $type;
	/** @var array<string, PropertyArgumentList> */
	private $propertyArgumentLists = array();

	public function __construct(CollectionTypeHelper $typeHelper)
	{
		$this->typeHelper = $typeHelper;
		$this->type = $typeHelper->getType();

		if (!($this->type instanceof Search))
		{
			throw new TypeException("Type %1 does not support searching", $this->typeHelper->getName());
		}
	}

	public function append($propertyName, QueryArgument $argument)
	{
		$property = $this->type->getProperty($propertyName);
		if (is_null($property))
		{
			throw new TypeException("Property \"%1\" does not exist in type %2", $propertyName, $this->typeHelper->getName());
		}

		if (!$property->isValidArgument($argument))
		{
			throw new TypeException("The specified argument is not valid for property %1::%2", $this->typeHelper->getName(), $property);
		}

		$this->getArgumentList($propertyName)->append($argument);

		return $this;
	}

	/**
	 * Runs a callback function for each defined restriction.
	 * @param string|array	$propertyNames
	 * @param callback		$callback
	 * @return $this
	 */
	public function with($propertyNames, $callback)
	{
		if (is_string($propertyNames))
		{
			$propertyNames = array($propertyNames);
		}

		foreach($propertyNames as $name)
		{
			if (!isset($this->propertyArgumentLists[$name])) continue;

			$values = $this->propertyArgumentLists[$name];

			// TODO
			if (is_array($values))
			{
				foreach($values as $value)
				{
					call_user_func($callback, $value, $name);
				}
			}
			else
			{
				call_user_func($callback, $values, $name);
			}
		}

		return $this;
	}

	/**
	 * Returns an object with a list of arguments for the named property.
	 * @param string	$propertyName
	 * @return PropertyArgumentList
	 * @throws TypeException	If the named property does not exist.
	 */
	public function getArgumentList($propertyName)
	{
		if (isset($this->propertyArgumentLists[$propertyName]))
		{
			return $this->propertyArgumentLists[$propertyName];
		}
		else
		{
			$propertyTypeHelper = $this->typeHelper->getSearchPropertyTypeHelper($propertyName);
			$property = $this->type->getProperty($propertyName);

			return $this->propertyArgumentLists[$propertyName] = new PropertyArgumentList($propertyTypeHelper, $property);
		}
	}
}