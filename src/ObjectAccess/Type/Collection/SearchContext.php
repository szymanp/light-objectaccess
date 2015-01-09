<?php
namespace Light\ObjectAccess\Type\Collection;

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
	 * @return object	An object, if there is one; otherwise, NULL.
	 */
	public function getContextObject();
}