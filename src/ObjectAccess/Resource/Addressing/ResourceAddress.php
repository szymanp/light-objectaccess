<?php
namespace Light\ObjectAccess\Resource\Addressing;

use Light\ObjectAccess\Query\Scope;

/**
 * An absolute address for a resource.
 */
interface ResourceAddress
{
	/**
	 * Returns a new address with the given scope appended at the end.
	 * @param Scope $scope
	 * @return ResourceAddress	A new ResourceAddress object representing the original address
	 *                          with the scope object appended at the end.
	 */
	public function appendScope(Scope $scope);

	/**
	 * Returns a new address with the given element appended at the end.
	 * @param string	$pathElement
	 * @return ResourceAddress	A new ResourceAddress object representing the original address
	 * 							with the new element appended at the end.
	 */
	public function appendElement($pathElement);

	/**
	 * Returns true if the address has a string representation.
	 * @return boolean
	 */
	public function hasStringForm();

	/**
	 * Returns the string representation of this address.
	 * @return string	An address string, if it is available; otherwise, NULL.
	 */
	public function getAsString();
}