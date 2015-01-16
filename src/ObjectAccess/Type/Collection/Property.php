<?php
namespace Light\ObjectAccess\Type\Collection;

interface Property
{
	/**
	 * Returns the name of the property.
	 * @return string
	 */
	public function getName();

	/**
	 * Returns the type of the property.
	 * @return string
	 */
	public function getTypeName();
}