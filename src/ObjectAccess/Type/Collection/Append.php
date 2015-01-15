<?php 
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Resource\ResolvedCollection;

/**
 * An interface for CollectionTypes that support appending elements to a collection.
 *
 */
interface Append
{
	/**
	 * Appends a value to the collection
	 * @param ResolvedCollection	$collection
	 * @param mixed					$value
	 */
	public function appendValue(ResolvedCollection $collection, $value);
}