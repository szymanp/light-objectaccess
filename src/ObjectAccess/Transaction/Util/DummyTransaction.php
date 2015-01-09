<?php
namespace Light\ObjectAccess\Transaction\Util;

use Light\ObjectAccess\Resource\ResolvedValue;
use Light\ObjectAccess\Transaction\Transaction;

final class DummyTransaction implements Transaction
{
	public function saveDirty(ResolvedValue $resource)
	{
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