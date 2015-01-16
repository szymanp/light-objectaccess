<?php
namespace Light\ObjectAccess\Query;

use Light\ObjectAccess\Exception\TypeException;
use Light\ObjectAccess\Query\Argument\QueryArgument;
use Light\ObjectAccess\Type\CollectionTypeHelper;

abstract class Query
{
	/**
	 * Returns a new Query object.
	 * @param CollectionTypeHelper $typeHelper
	 * @return QueryConcrete
	 */
	public static function create(CollectionTypeHelper $typeHelper)
	{
		return new QueryConcrete($typeHelper);
	}

	abstract public function append($propertyName, QueryArgument $argument);

	/**
	 * Runs a callback function for each defined restriction.
	 * @param string|array	$propertyNames
	 * @param callback		$callback
	 * @return $this
	 */
	abstract public function with($propertyNames, $callback);

	/**
	 * Returns an object with a list of arguments for the named property.
	 * @param string	$propertyName
	 * @return PropertyArgumentList
	 * @throws TypeException	If the named property does not exist.
	 */
	abstract public function getArgumentList($propertyName);
}