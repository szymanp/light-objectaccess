<?php
namespace Light\ObjectAccess\Transaction\Util;

use Light\ObjectAccess\Resource\ResolvedResource;
use Light\ObjectAccess\Transaction\Transaction;

final class DummyTransaction implements Transaction
{
	/** @var ResolvedResource[] */
	private $created = array();
	/** @var ResolvedResource[] */
	private $changed = array();
	/** @var ResolvedResource[] */
	private $deleted = array();

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


	public function begin()
	{
	}

	public function commit()
	{
	}

	public function rollback()
	{
	}
}