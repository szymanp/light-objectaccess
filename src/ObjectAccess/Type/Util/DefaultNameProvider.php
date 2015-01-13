<?php
namespace Light\ObjectAccess\Type\Util;

use Light\ObjectAccess\Type\CollectionType;
use Light\ObjectAccess\Type\ComplexType;
use Light\ObjectAccess\Type\NameProvider;
use Light\ObjectAccess\Type\SimpleType;
use Light\ObjectAccess\Type\Type;

class DefaultNameProvider implements NameProvider
{
	/** @var string */
	private $prefix;

	/**
	 * Returns the class prefix that will be stripped from class names.
	 * @return string
	 */
	public function getPrefix()
	{
		return $this->prefix;
	}

	/**
	 * Sets the class prefix that will be stripped from class names.
	 *
	 * For example, if the prefix is set to "Application\Model", then the name for the class
	 * "Application\Model\User" would be "User".
	 *
	 * @param string $prefix
	 */
	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;
	}

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
			return $this->stripPrefix($type->getClassName());
		}
		elseif ($type instanceof CollectionType)
		{
			return $type->getBaseTypeName() . "[]";
		}
		throw new \LogicException();
	}

	private function stripPrefix($name)
	{
		if (!is_null($this->prefix)
			&& substr($name, 0, strlen($this->prefix) + 1) == $this->prefix + "\\")
		{
			return substr($name, strlen($this->prefix) + 1);
		}
		else
		{
			return $name;
		}
	}
}