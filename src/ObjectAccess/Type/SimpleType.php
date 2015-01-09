<?php
namespace Light\ObjectAccess\Type;

interface SimpleType extends Type
{
	/**
	 * Returns the PHP type of the value supported by this SimpleType.
	 * @return string
	 */
	public function getPhpType();
}