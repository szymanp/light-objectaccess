<?php 
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Transaction\Transaction;

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
	 * @param Transaction			$transaction
	 */
	public function removeValue(ResolvedCollection $collection, $value, Transaction $transaction);
}
