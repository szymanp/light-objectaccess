<?php
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Resource\ResolvedCollection;

interface Iterate
{
	/**
	 * Returns an Iterator over the elements (key-value pairs) in the given collection.
	 * @param ResolvedCollection $collection
	 * @return \Iterator
	 */
	public function getIterator(ResolvedCollection $collection);
}