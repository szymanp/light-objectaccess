<?php
namespace Light\ObjectAccess\Resource\Addressing;

use Light\ObjectAccess\Query\Scope;

/**
 * An absolute address for a resource.
 */
interface ResourceAddress
{
	/**
	 * @param Scope $scope
	 * @return ResourceAddress	A new ResourceAddress object representing the original address
	 *                          with the scope object appended at the end.
	 */
	public function appendScope(Scope $scope);

	/**
	 * @param string	$pathElement
	 * @return ResourceAddress	A new ResourceAddress object representing the original address
	 * 							with the new element appended at the end.
	 */
	public function appendElement($pathElement);

	public function hasStringForm();

	public function getAsString();
}