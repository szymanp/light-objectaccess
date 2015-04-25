<?php
namespace Light\ObjectAccess\Query;

use Light\Exception\NotImplementedException;
use Light\ObjectAccess\Exception\TypeException;
use Light\ObjectAccess\Query\Argument\QueryArgument;
use Light\ObjectAccess\Type\CollectionTypeHelper;

final class QueryEmpty extends Query
{
	/** @inheritdoc */
	public function append($propertyName, QueryArgument $argument)
	{
		return $this;
	}

	/** @inheritdoc */
	public function with($propertyNames, $callback)
	{
		return $this;
	}

	/** @inheritdoc */
	public function getArgumentList($propertyName)
	{
		throw new NotImplementedException();
	}

	/** @inheritdoc */
	public function getArgumentLists()
	{
		return array();
	}

	/** @inheritdoc */
	public function getCollectionTypeHelper()
	{
		throw new NotImplementedException();
	}

}