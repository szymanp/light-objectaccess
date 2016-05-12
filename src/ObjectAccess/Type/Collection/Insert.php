<?php 
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Transaction\Transaction;
use Light\ObjectAccess\Exception\InvalidActionException;

/**
 * An interface for CollectionTypes that support inserting elements into a collection at arbitrary positions.
 *
 * When an element is inserted, the collection should assign an index or a "key" to the element.
 * The key should allow the element to be retrieved using {@link CollectionType::getElementAtKey}.
 *
 */
interface Insert
{
	/**
	 * Inserts a value into the collection at a specified position.
	 * @param ResolvedCollection	$collection
	 * @param mixed					$value
	 * @param mixed					$insertBefore	A key or value.
	 * @param Transaction			$transaction
	 * @return string|integer		The index or key that was assigned to the element.
     * @throws InvalidActionException   Thrown if the given element does not qualify for appending to the collection.
	 */
	public function insertValue(ResolvedCollection $collection, $value, $insertBefore, Transaction $transaction);
}