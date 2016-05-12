<?php 
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Transaction\Transaction;
use Light\ObjectAccess\Exception\InvalidActionException;

/**
 * An interface for CollectionTypes that support removing elements from collection by value.
 *
 */
interface Remove
{
	/**
	 * Removes a given value from the collection.
	 * @todo What if the value is present multiple times in the collection?
	 * @param ResolvedCollection	$collection
	 * @param mixed					$value
	 * @param Transaction			$transaction
     * @throws InvalidActionException   Thrown if the specified element doesn't qualify for removal.
	 */
	public function removeValue(ResolvedCollection $collection, $value, Transaction $transaction);
}
