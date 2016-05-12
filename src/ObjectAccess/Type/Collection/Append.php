<?php 
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Transaction\Transaction;
use Light\ObjectAccess\Exception\InvalidActionException;

/**
 * An interface for CollectionTypes that support appending elements to a collection.
 *
 * This interface does not specify the position in the collection at which the new element should be appended.
 * The collection should itself assign a "key" to the object.
 *
 * The key assigned to the object should allow the element to be retrieved using {@link CollectionType::getElementAtKey}.
 *
 */
interface Append
{
	/**
	 * Appends a value to the collection.
	 * @param ResolvedCollection	$collection
	 * @param mixed					$value
	 * @param Transaction			$transaction
	 * @return string|integer		The index or key which was assigned to this element in the collection.
     * @throws InvalidActionException   Thrown if the given element does not qualify for appending to the collection.
	 */
	public function appendValue(ResolvedCollection $collection, $value, Transaction $transaction);
}