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

	/**
	 * Returns a new query object that cannot be modified and is empty.
	 * @return QueryEmpty
	 */
	public static function emptyQuery()
	{
		return new QueryEmpty();
	}

	/**
	 * Appends a new query argument to this property.
	 * @param string        $propertyName
	 * @param QueryArgument $argument
	 * @return $this
	 */
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

	/**
	 * Returns argument lists for all properties.
	 * @return array<string, PropertyArgumentList>
	 */
	abstract public function getArgumentLists();

	/**
	 * Returns a type helper for the collection this query will operate on.
	 * @return CollectionTypeHelper
	 */
	abstract public function getCollectionTypeHelper();
}