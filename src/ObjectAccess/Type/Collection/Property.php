<?php
namespace Light\ObjectAccess\Type\Collection;

use Light\ObjectAccess\Query\Argument\QueryArgument;

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

	/**
	 * Returns true if the given query argument is valid for this property.
	 * @param QueryArgument $queryArgument
	 * @return boolean
	 */
	public function isValidArgument(QueryArgument $queryArgument);
}