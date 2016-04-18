<?php
namespace Light\ObjectAccess\Resource\Util;

use Light\ObjectAccess\Query\Scope;
use Light\ObjectAccess\Resource\Addressing\ResourceAddress;

/**
 * A ResourceAddress that indicates a lack of address.
 */
final class EmptyResourceAddress implements ResourceAddress
{
	private static $instance;

	/**
	 * Returns an address instance.
	 * @return EmptyResourceAddress
	 */
	public static function create()
	{
		if (is_null(self::$instance)) self::$instance = new self;
		return self::$instance;
	}

	private function __construct()
	{
		// A private constructor - use create() instead.
	}

	/** @inheritdoc */
	public function appendScope(Scope $scope)
	{
		// Appending to an EmptyResourceAddress doesn't change the address - it is still empty.
		return $this;
	}

	/** @inheritdoc */
	public function appendElement($pathElement)
	{
		// Appending to an EmptyResourceAddress doesn't change the address - it is still empty.
		return $this;
	}

	/** @inheritdoc */
	public function hasStringForm()
	{
		return false;
	}

	/** @inheritdoc */
	public function getAsString()
	{
		return "(empty)";
	}
}