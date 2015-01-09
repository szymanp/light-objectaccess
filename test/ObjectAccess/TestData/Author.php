<?php
namespace Light\ObjectAccess\TestData;

use Light\ObjectAccess\Type\Util\DefaultComplexType;
use Light\ObjectAccess\Type\Util\DefaultProperty;

class Author
{
	public $id;
	public $name;
	public $age;

	/**
	 * @return DefaultComplexType
	 */
	public static function createType()
	{
		$type = new DefaultComplexType("Light\ObjectAccess\TestData\Author");
		$type->addProperty(new DefaultProperty("id"));
		$type->addProperty(new DefaultProperty("name"));
		$type->addProperty(new DefaultProperty("age"));
		return $type;
	}
}