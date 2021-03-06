<?php
namespace Light\ObjectAccess\TestData;

use Szyman\Exception\Exception;
use Szyman\Exception\NotImplementedException;
use Light\ObjectAccess\Exception\TypeException;
use Light\ObjectAccess\Query\Argument\Criterion;
use Light\ObjectAccess\Query\Query;
use Light\ObjectAccess\Query\QueryEmpty;
use Light\ObjectAccess\Resource\ResolvedNull;
use Light\ObjectAccess\Resource\ResolvedValue;
use Light\ObjectAccess\Type\ComplexTypeHelper;

class QueryFilterIterator extends \FilterIterator
{
	/** @var Query */
	private $query;
	/** @var ComplexTypeHelper */
	private $elementTypeHelper;

	public function __construct(\Iterator $iterator, Query $query)
	{
		parent::__construct($iterator);
		$this->query = $query;

		if (!($query instanceof QueryEmpty))
		{
			$this->elementTypeHelper = $this->query->getCollectionTypeHelper()->getBaseTypeHelper();
			if (!($this->elementTypeHelper instanceof ComplexTypeHelper))
			{
				throw new TypeException("This class only supports collections of objects");
			}
		}
	}

	/**
	 * Check whether the current element of the iterator is acceptable
	 * @link http://php.net/manual/en/filteriterator.accept.php
	 * @return bool true if the current element is acceptable, otherwise false.
	 */
	public function accept()
	{
		$element = parent::current();

		if ($this->query instanceof QueryEmpty)
		{
			return true;
		}

		foreach($this->query->getArgumentLists() as $propertyName => $argumentList)
		{
			// Extract the current property value
			$resource = $this->elementTypeHelper->readProperty($this->elementTypeHelper->resolveValue($element), $propertyName);
			if ($resource instanceof ResolvedValue)
			{
				$value = $resource->getValue();
			}
			elseif ($resource instanceof ResolvedNull)
			{
				$value = null;
			}
			else
			{
				throw new NotImplementedException();
			}

			foreach($argumentList as $argument)
			{
				if ($argument instanceof Criterion)
				{
					if (!$this->compare($argument, $value))
					{
						return false;
					}
				}
				else
				{
					throw new NotImplementedException();
				}
			}
		}

		return true;
	}

	/**
	 * Returns true if the criterion matches the value.
	 * @param Criterion $criterion
	 * @param mixed     $value
	 * @return bool
	 * @throws Exception
	 */
	private function compare(Criterion $criterion, $value)
	{
		switch ($criterion->getOperator())
		{
			case Criterion::EQ:
				return $value === $criterion->getValue();
			case Criterion::GT:
				return $value > $criterion->getValue();
			case Criterion::LT:
				return $value < $criterion->getValue();
			default:
				throw new Exception("Operator \"%1\" is not supported", $criterion->getOperator());
		}
	}

}