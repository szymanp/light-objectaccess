<?php 
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Query\Scope;
use Light\ObjectAccess\Type\Complex\Value;

interface Search
{
	/**
	 * Returns a specification of a collection property.
	 * @param string $propertyName
	 * @return Property	A Property object, if the property exists; otherwise, NULL.
	 */
	public function getProperty($propertyName);

	/**
	 * Returns all objects matching the scope.
	 * @param ResolvedCollection 	$collection
	 * @param Scope					$scope
	 * @param SearchContext			$context
	 * @return \Iterator	An iterator over all objects matching the scope.
	 *                   	The key of the iterator should indicate the key of the object in the collection.
	 */
	public function find(ResolvedCollection $collection, Scope $scope, SearchContext $context);
}