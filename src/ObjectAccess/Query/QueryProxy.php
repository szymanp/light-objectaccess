<?php
namespace Light\ObjectAccess\Query;

use Light\ObjectAccess\Exception\TypeException;
use Light\ObjectAccess\Query\Argument\QueryArgument;
use Light\ObjectAccess\Type\CollectionTypeHelper;

/**
 * A proxy for a concrete query.
 *
 * This class can be used if a concrete query cannot be constructed, for example, because type
 * information is not available at the time. ObjectAccess classes will call {@link prepare()}
 * with appropriate type information when the query should be used.
 */
abstract class QueryProxy extends Query
{
	/** @var Query */
	private $query;

	/**
	 * Creates a concrete Query object.
	 * @return Query
	 */
	abstract protected function createQuery(CollectionTypeHelper $helper);

	final public function prepare(CollectionTypeHelper $helper)
	{
		// TODO
	}

	final public function append($propertyName, QueryArgument $argument)
	{
		$this->query->append($propertyName, $argument);
		return $this;
	}

	/**
	 * Runs a callback function for each defined restriction.
	 * @param string|array $propertyNames
	 * @param callback     $callback
	 * @return $this
	 */
	final public function with($propertyNames, $callback)
	{
		$this->query->with($propertyNames, $callback);
		return $this;
	}

	/**
	 * Returns an object with a list of arguments for the named property.
	 * @param string $propertyName
	 * @return PropertyArgumentList
	 * @throws TypeException    If the named property does not exist.
	 */
	final public function getArgumentList($propertyName)
	{
		return $this->query->getArgumentList($propertyName);
	}

	/**
	 * Returns argument lists for all properties.
	 * @return array<string, PropertyArgumentList>
	 */
	final public function getArgumentLists()
	{
		return $this->query->getArgumentLists();
	}

	/**
	 * Returns a type helper for the collection this query will operate on.
	 * @return CollectionTypeHelper
	 */
	final public function getCollectionTypeHelper()
	{
		return $this->query->getCollectionTypeHelper();
	}

}