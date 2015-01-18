<?php
namespace Light\ObjectAccess\Query\Scope;

use Light\ObjectAccess\Query\Scope;

/**
 * A scope that uniquely identifies an element by its key.
 */
final class KeyScope extends Scope
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