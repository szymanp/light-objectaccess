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
		// TODO
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
		// TODO
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