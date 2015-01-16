<?php
namespace Light\ObjectAccess\Type\Util;

use Light\ObjectAccess\Query\Argument\QueryArgument;
use Light\ObjectAccess\Type\Collection\FilterableProperty;

class DefaultFilterableProperty implements FilterableProperty
{
	/** @var string */
	private $name;
	/** @var string */
	private $typeName;

	/**
	 * Constructs a new DefaultFilterableProperty.
	 * @param string	$name
	 * @param string	$typeName
	 */
	public function __construct($name, $typeName = null)
	{
		$this->name = $name;
		$this->typeName = $typeName;
	}

	/**
	 * Returns the name of the property.
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Returns the type of the property.
	 * @return string
	 */
	public function getTypeName()
	{
		return $this->typeName;
	}

	/**
	 * Returns true if the given query argument is valid for this property.
	 * @param QueryArgument $queryArgument
	 * @return boolean
	 */
	public function isValidArgument(QueryArgument $queryArgument)
	{
		return true;
	}

}