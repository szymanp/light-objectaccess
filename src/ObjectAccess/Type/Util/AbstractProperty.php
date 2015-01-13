<?php
namespace Light\ObjectAccess\Type\Util;

use Light\ObjectAccess\Type\Complex\Property;

/**
 * Base class for Property objects.
 */
abstract class AbstractProperty implements Property
{
	/** @var string */
	protected $name;
	/** @var string */
	protected $typeName;

	/**
	 * Constructs a new AbstractProperty.
	 * @param string    $name
	 * @param string	$typeName
	 */
	public function __construct($name, $typeName = null)
	{
		$this->name = $name;
		$this->typeName = $typeName;
	}

	/**
	 * Returns the name of this property.
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Returns the name of this property's type.
	 * @return string
	 */
	public function getTypeName()
	{
		return $this->typeName;
	}
}