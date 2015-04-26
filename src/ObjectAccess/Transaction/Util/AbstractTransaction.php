<?php
namespace Light\ObjectAccess\Transaction\Util;

use Light\ObjectAccess\Resource\ResolvedResource;
use Light\ObjectAccess\Transaction\Transaction;

/**
 * A base class for implementations of the Transaction interface.
 */
abstract class AbstractTransaction implements Transaction
{
	/** @var ResolvedResource[] */
	protected $created = array();
	/** @var ResolvedResource[] */
	protected $changed = array();
	/** @var ResolvedResource[] */
	protected $deleted = array();

	/**
	 * @inheritdoc
	 */
	public function markAsCreated(ResolvedResource $resource)
	{
		if (!in_array($resource, $this->created, true))
		{
			$this->created[] = $resource;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function markAsChanged(ResolvedResource $resource)
	{
		if (!in_array($resource, $this->changed, true))
		{
			$this->changed[] = $resource;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function markAsDeleted(ResolvedResource $resource)
	{
		if (!in_array($resource, $this->deleted, true))
		{
			$this->deleted[] = $resource;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function getCreatedResources()
	{
		return $this->created;
	}

	/**
	 * @inheritdoc
	 */
	public function getChangedResources()
	{
		return $this->changed;
	}

	/**
	 * @inheritdoc
	 */
	public function getDeletedResources()
	{
		return $this->deleted;
	}
}