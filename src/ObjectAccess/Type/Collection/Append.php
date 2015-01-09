<?php 
namespace Light\ObjectAccess\Type\Collection;

/**
 * An interface for CollectionTypes that support appending elements to a collection.
 *
 */
interface Append
{
	/**
	 * Appends a value to the collection
	 * @param mixed	$collection
	 * @param mixed	$value
	 */
	public function appendValue($collection, $value);
}