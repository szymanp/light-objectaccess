<?php 
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Resource\ResolvedCollection;

/**
 * An interface for CollectionTypes that support removing elements from collection.
 *
 */
interface Remove
{
	/**
	 * Removes a given value from the collection.
	 * @param ResolvedCollection	$collection
	 * @param mixed					$value
	 */
	public function removeValue(ResolvedCollection $collection, $value);
}
