<?php
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Transaction\Transaction;

/**
 * An interface for CollectionTypes that support appending elements to a collection at a given key.
 *
 * If an element at the specified key in the collection already exists, then it is replaced with the new one.
 * The collection may change the element so that it adheres to the specified key.
 *
 * The given key should allow the element to be retrieved using {@link CollectionType::getElementAtKey}.
 */
interface SetElementAtKey
{
	/**
	 * Sets the value of the element in the collection at the specified key.
	 *
	 * @param ResolvedCollection	$collection
	 * @param string|integer		$key
	 * @param mixed					$value
	 * @param Transaction			$transaction
	 */
	public function setElementAtKey(ResolvedCollection $collection, $key, $value, Transaction $transaction);
}