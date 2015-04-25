<?php
namespace Light\ObjectAccess\Transaction;

use Light\ObjectAccess\Resource\ResolvedResource;

/**
 * A transaction encompassing changes to resources.
 *
 */
interface Transaction
{
	public function markAsCreated(ResolvedResource $resource);

	public function markAsChanged(ResolvedResource $resource);

	public function markAsDeleted(ResolvedResource $resource);

	/**
	 * Returns a list of all resources that were created in this transaction.
	 * @return ResolvedResource[]
	 */
	public function getCreatedResources();

	/**
	 * Returns a list of all resources that were changed in this transaction.
	 * @return ResolvedResource[]
	 */
	public function getChangedResources();

	/**
	 * Returns a list of all resources that were deleted in this transaction.
	 * @return ResolvedResource[]
	 */
	public function getDeletedResources();

	public function begin();
	
	public function commit();
	
	public function rollback();
}