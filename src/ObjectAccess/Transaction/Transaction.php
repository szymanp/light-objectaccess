<?php
namespace Light\ObjectAccess\Transaction;

use Light\ObjectAccess\Resource\ResolvedValue;

/**
 * A transaction encompassing changes to resources.
 *
 */
interface Transaction
{
	public function saveDirty(ResolvedValue $resource);

	public function begin();
	
	public function commit();
	
	public function rollback();
}