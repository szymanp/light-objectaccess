<?php
namespace Light\ObjectAccess\Query;

use Light\Exception\Exception;

/**
 * Scope specifies the filtering restrictions and ordering of a collection.
 *
 * Scope can be created from an URL - in which case it comes in two forms:
 * - a path scope: http://hostname/endpoint/post/(count=5,offset=10)/title (via PathScopeParser)
 * - target scope: http://hostname/endpoint/post?count=5&offset=10 (via TargetScopeParser)
 * Scope can also be passed inside the body of the request. Then it is parsed by an appropriate format handler,
 * e.g. JsonScopeReader.
 */
abstract class Scope
{
	/**
	 * A scope that places no restrictions on the collection.
	 * @return Scope
	 */
	public static function createEmptyScope()
	{
		return new Scope\EmptyScope();
	}

	/**
	 * A scope that identifies an element by its ordinal index.
	 * @param integer $index
	 * @return Scope
	 */
	public static function createWithIndex($index)
	{
		return new Scope\IndexScope($index);
	}

	/**
	 * A scope that uniquely identifies an element by its key.
	 * @param mixed $key
	 * @return Scope
	 */
	public static function createWithKey($key)
	{
		return new Scope\KeyScope($key);
	}

	/**
	 * A scope that restricts a collection using a query.
	 * @param Query 	$query
	 * @param integer	$count
	 * @param integer	$offset
	 * @return Scope
	 */
	public static function createWithQuery(Query $query, $count = null, $offset = null)
	{
		return new Scope\QueryScope($query, $count, $offset);
	}

	protected function __construct()
	{
		// Empty
	}
}