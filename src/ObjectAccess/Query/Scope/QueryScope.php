<?php
namespace Light\ObjectAccess\Query\Scope;

use Light\ObjectAccess\Query\Query;
use Light\ObjectAccess\Query\Scope;

/**
 * A scope that identifies zero or more elements in a collection by a query expression.
 */
final class QueryScope extends Scope
{
	/** @var Query */
	private $query;
	/** @var integer */
	private $offset;
	/** @var integer */
	private $count;

	protected function __construct(Query $query, $count = null, $offset = null)
	{
		$this->query = $query;
	}

	/**
	 * @return Query
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * @return int    An integer, if set; otherwise, null.
	 */
	public function getOffset()
	{
		return $this->offset;
	}

	/**
	 * @return int    An integer, if set; otherwise, null.
	 */
	public function getCount()
	{
		return $this->count;
	}
}