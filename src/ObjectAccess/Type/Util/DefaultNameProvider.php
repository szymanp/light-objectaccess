<?php
namespace Light\ObjectAccess\Type\Util;

use Light\ObjectAccess\Type\CollectionType;
use Light\ObjectAccess\Type\ComplexType;
use Light\ObjectAccess\Type\NameProvider;
use Light\ObjectAccess\Type\SimpleType;
use Light\ObjectAccess\Type\Type;

class DefaultNameProvider implements NameProvider
{
	/**
	 * Returns the URI for the given type.
	 * @param Type $type
	 * @return string    An URI for the specified type.
	 */
	public function getTypeUri(Type $type)
	{
		return "php:" . $this->getTypeName($type);
	}

	/**
	 * Returns the name for the given type.
	 * @param Type $type
	 * @return string    A name for the given type.
	 *                     Names for collection types must end in [].
	 */
	public function getTypeName(Type $type)
	{
		if ($type instanceof SimpleType)
		{
			return $type->getPhpType();
		}
		elseif ($type instanceof ComplexType)
		{
			return $type->getClassName();
		}
		elseif ($type instanceof CollectionType)
		{
			return $type->getBaseTypeName() . "[]";
		}
		throw new \LogicException();
	}
}