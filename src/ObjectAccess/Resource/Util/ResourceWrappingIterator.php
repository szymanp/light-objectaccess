<?php
namespace Light\ObjectAccess\Resource\Util;

use Light\ObjectAccess\Type\CollectionTypeHelper;
use Light\ObjectAccess\Type\TypeHelper;
use Light\ObjectAccess\Resource\ResolvedResource;
use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Query\Scope;
use Light\ObjectAccess\Type\Complex\Value;
use Light\ObjectAccess\Resource\ResolvedValue;
use Light\ObjectAccess\Type\Complex\Value_Concrete;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;
use Light\Exception\NotImplementedException;

/**
 * An iterator that wraps objects from another iterator into resource objects.
 */
final class ResourceWrappingIterator implements \Iterator
{
	/** @var ResolvedCollection */
	private $collection;
	/** @var TypeHelper */
	private $baseTypeHelper;
	/** @var \Iterator */
	private $iterator;

	/**
	 * Creates a new ResourceWrappingIterator.
	 * @param \Light\ObjectAccess\Resource\ResolvedCollection $collection	The collection being iterated.
	 * @param \Iterator                                       $iterator		An iterator over the collection.
	 *                                                                   	The iterator may return plain values,
	 *                                                                   	or {@link Value} objects.
	 */
	public function __construct(ResolvedCollection $collection, \Iterator $iterator)
	{
		$this->collection = $collection;
		$this->baseTypeHelper = $collection->getTypeHelper()->getBaseTypeHelper();
		$this->iterator = $iterator;
	}

	/**
	 * Return the current element
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return ResolvedResource
	 */
	public function current()
	{
		$key = $this->key();
		$origin = Origin::elementInCollection($this->collection, $key);
		$resourceAddress = $this->collection->getAddress()->appendScope(Scope::createWithKey($key));

		$current = $this->iterator->current();

		if ($current instanceof Value)
		{
			if ($current instanceof Value_Concrete)
			{
				return ResolvedValue::create($this->baseTypeHelper, $current, $resourceAddress, $origin);
			}
			else
			{
				if ($this->baseTypeHelper instanceof CollectionTypeHelper)
				{
					return new ResolvedCollectionResource($this->baseTypeHelper, $resourceAddress, $origin);
				}
				else
				{
					// We only support unavailable values for collections.
					throw new NotImplementedException;
				}
			}
		}
		else
		{
			// We allow the inner iterator to return plain values.
			// We treat them as concrete values.
			return ResolvedValue::create($this->baseTypeHelper, $current, $resourceAddress, $origin);
		}
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Move forward to next element
	 * @link http://php.net/manual/en/iterator.next.php
	 * @return void Any returned value is ignored.
	 */
	public function next()
	{
		return $this->iterator->next();
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Return the key of the current element
	 * @link http://php.net/manual/en/iterator.key.php
	 * @return mixed scalar on success, or null on failure.
	 */
	public function key()
	{
		return $this->iterator->key();
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Checks if current position is valid
	 * @link http://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 * Returns true on success or false on failure.
	 */
	public function valid()
	{
		return $this->iterator->valid();
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Rewind the Iterator to the first element
	 * @link http://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 */
	public function rewind()
	{
		return $this->iterator->rewind();
	}

}