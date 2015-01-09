<?php 
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectService\Resource\Query\Scope;

interface Search
{
	/**
	 * Returns all objects matching the scope.
	 * @param Scope		$scope
	 * @param SearchContext	$context
	 * @return object[]
	 */
	public function find(Scope $scope, SearchContext $context);
}