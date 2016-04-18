<?php 
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Transaction\Transaction;

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
	 */
	public function removeValue(ResolvedCollection $collection, $value, Transaction $transaction);
}
