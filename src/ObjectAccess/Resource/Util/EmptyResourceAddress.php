<?php
namespace Light\ObjectAccess\Resource\Util;

use Light\ObjectAccess\Query\Scope;
use Light\ObjectAccess\Resource\Addressing\ResourceAddress;

/**
 * A ResourceAddress that doesn't keep any address.
 */
final class EmptyResourceAddress implements ResourceAddress
{
	/**
	 * @return EmptyResourceAddress
	 */
	public static function create()
	{
		return new self;
	}

	private function __construct()
	{
	}

	/**
	 * @param Scope $scope
	 * @return ResourceAddress    A new ResourceAddress object representing the original address
	 *                          with the scope object appended at the end.
	 */
	public function appendScope(Scope $scope)
	{
		return $this;
	}

	/**
	 * @param string $pathElement
	 * @return ResourceAddress    A new ResourceAddress object representing the original address
	 *                            with the new element appended at the end.
	 */
	public function appendElement($pathElement)
	{
		return $this;
	}

	public function hasStringForm()
	{
		return true;
	}

	public function getAsString()
	{
		return "(empty)";
	}

}