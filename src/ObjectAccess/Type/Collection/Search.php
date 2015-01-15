<?php 
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Query\Scope;
use Light\ObjectAccess\Type\Complex\Value;

interface Search
{
	/**
	 * Returns all objects matching the scope.
	 * @param ResolvedCollection 	$collection
	 * @param Scope					$scope
	 * @param SearchContext			$context
	 * @return Value[]
	 */
	public function find(ResolvedCollection $collection, Scope $scope, SearchContext $context);
}