<?php 
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Type\Type;

/**
 * A registry of URIs for types and resources.
 * 
 * Classes implementing this interface provide URIs and names for types.
 */
interface NameProvider
{
	/**
	 * Returns the URI for the given type.
	 * @param Type $type
	 * @throws \Exception	If URI for this type cannot be obtained.
	 * @return string	An URI for the specified type.
	 */
	public function getTypeUri(Type $type);

	/**
	 * Returns the name for the given type.
	 * @param Type $type
	 * @throws \Exception	If the name for this type cannot be obtained.
	 * @return string    A name for the given type.
	 * 					 Names for collection types must end in [].
	 */
	public function getTypeName(Type $type);
}
