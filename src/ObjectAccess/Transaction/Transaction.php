<?php
namespace Light\ObjectAccess\Transaction;

use Light\ObjectAccess\Resource\ResolvedResource;

/**
 * A transaction encompassing changes to resources.
 */
interface Transaction
{
	/**
	 * The resource has been created within this transaction.
	 * @param ResolvedResoure	$resource
	 */
	public function markAsCreated(ResolvedResource $resource);

	/**
	 * The resource has been changed within this transaction.
	 * @param ResolvedResoure	$resource
	 */
	public function markAsChanged(ResolvedResource $resource);

	/**
	 * The resource has been deleted with this transaction.
	 * @param ResolvedResoure	$resource
	 */
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

	/**
	 * Begin the transaction.
	 */
	public function begin();

	/**
	 * Transfer changes done in this transaction, but do not commit yet.
	 *
	 * With some ORM systems object are first modified, then the changes are transferred to the database,
	 * and finally, the database changes are committed. This method is intended to carry out the step
	 * of transferring the changes to the database.
	 */
	public function transfer();
	
	/**
	 * Commit the changes done in this transaction.
	 */
	public function commit();

	/**
	 * Rollback the changes done in this transaction.
	 */
	public function rollback();
}
