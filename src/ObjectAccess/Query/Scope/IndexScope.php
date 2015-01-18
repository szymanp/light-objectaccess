<?php
namespace Light\ObjectAccess\Query\Scope;

use Light\ObjectAccess\Query\Scope;

/**
 * A scope that identifies an element by its ordinal index.
 */
final class IndexScope extends Scope
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