<?php 
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Transaction\Transaction;

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
	 * @param Transaction			$transaction
	 */
	public function appendValue(ResolvedCollection $collection, $value, Transaction $transaction);
}