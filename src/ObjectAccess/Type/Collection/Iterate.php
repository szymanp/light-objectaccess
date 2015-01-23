<?php
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Resource\ResolvedCollectionResource;
use Light\ObjectAccess\Resource\ResolvedCollectionValue;

/**
 * An interface for collections whose values can be traversed.
 *
 */
interface Iterate
{
	/**
	 * Returns an Iterator over the elements in the given collection.
	 * @param ResolvedCollectionValue $collection
	 * @return \Iterator	An iterator over the elements in the collection.
	 *                   	The key of the iterator should indicate the key of the object in the collection.
	 */
	public function getIterator(ResolvedCollectionValue $collection);

	/**
	 * Returns all the elements of the collection.
	 *
	 * This method will be called if all the elements of a collection need to be retrieved,
	 * for example when a search using {@link EmptyScope} is invoked.
	 *
	 * @param ResolvedCollectionResource $collection
	 * @return mixed	All the elements of the collection.
	 */
	public function read(ResolvedCollectionResource $collection);
}