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
		return new Scope_Empty();
	}

	/**
	 * A scope that identifies an element by its ordinal index.
	 * @param integer $index
	 * @return Scope
	 */
	public static function createWithIndex($index)
	{
		return new Scope_Index($index);
	}

	/**
	 * A scope that uniquely identifies an element by its key.
	 * @param mixed $key
	 * @return Scope
	 */
	public static function createWithKey($key)
	{
		return new Scope_Key($key);
	}

	protected function __construct()
	{
		// Empty
	}
}

/**
 * A scope that places no restrictions on the collection.
 */
final class Scope_Empty extends Scope
{

}

/**
 * A scope that identifies an element by its ordinal index.
 */
final class Scope_Index extends Scope
{
	/** @var integer */
	private $index;

	protected function __construct($index)
	{
		$this->index = (int)$index;
	}

	/**
	 * Returns the ordinal index of the element in the collection.
	 * @return int
	 */
	public function getIndex()
	{
		return $this->index;
	}
}

/**
 * A scope that uniquely identifies an element by its key.
 */
final class Scope_Key extends Scope
{
	/** @var integer|string */
	private $key;

	protected function __construct($key)
	{
		$this->key = $key;
	}

	/**
	 * @return int|string
	 */
	public function getKey()
	{
		return $this->key;
	}
}

/**
 * A scope that uniquely identifies an element by its value.
 */
final class Scope_Value extends Scope
{
	// TODO
}

/**
 * A scope that identifies zero or more elements in a collection by a query expression.
 */
final class Scope_Query extends Scope
{
	private $query;
	private $offset;
	private $count;
	// TODO
}