<?php 
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Query\Scope\QueryScope;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;

interface Search extends Iterate
{
	/**
	 * Returns a specification of a collection property.
	 * @param string $propertyName
	 * @return Property	A Property object, if the property exists; otherwise, NULL.
	 */
	public function getProperty($propertyName);

	/**
	 * Returns all elements of the collection matching the query scope.
	 * @param ResolvedCollectionResource	$collection
	 * @param QueryScope					$scope
	 * @param SearchContext					$context
	 * @return mixed	Elements of the collection matching the scope.
	 */
	public function find(ResolvedCollectionResource $collection, QueryScope $scope, SearchContext $context);
}