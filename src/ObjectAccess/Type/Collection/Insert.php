<?php 
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Resource\ResolvedCollection;

/**
 * An interface for CollectionTypes that support inserting elements into a collection at arbitrary positions.
 *
 */
interface Insert
{
	/**
	 * Inserts a value into the collection at a specified position.
	 * @param ResolvedCollection	$collection
	 * @param mixed					$value
	 * @param mixed					$insertBefore	A key or value.
	 */
	public function insertValue(ResolvedCollection $collection, $value, $insertBefore);
}