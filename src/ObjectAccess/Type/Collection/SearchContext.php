<?php
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Resource\Origin;

/**
 * Provides additional information for the retrieval of objects by a Search-enabled CollectionType.
 */
interface SearchContext
{
	/**
	 * Returns the related object for the query.
	 * 
	 * An object to be retrieved can be accessed via a property of another object.
	 * In this case, the object on which the property is defined is called the "related" object.
	 * 
	 * @return Origin
	 */
	public function getContextObject();
}