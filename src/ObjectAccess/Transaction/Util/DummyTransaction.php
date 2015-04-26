<?php
namespace Light\ObjectAccess\Transaction\Util;

use Light\ObjectAccess\Resource\ResolvedResource;
use Light\ObjectAccess\Transaction\Transaction;

final class DummyTransaction extends AbstractTransaction
{
	public function begin()
	{
	}

	public function transfer()
	{
	}

	public function commit()
	{
	}

	public function rollback()
	{
	}
}