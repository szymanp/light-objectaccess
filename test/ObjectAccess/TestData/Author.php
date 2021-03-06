<?php
namespace Light\ObjectAccess\TestData;

use Light\ObjectAccess\Type\Util\CollectionResourceProperty;
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
		$type = new DefaultComplexType(Author::class);
		$type->addProperty(new DefaultProperty("id", "int"));
		$type->addProperty(new DefaultProperty("name", "string"));
		$type->addProperty(new DefaultProperty("age", "int"));
		$type->addProperty(new CollectionResourceProperty("posts", Post::class . "[]"));
		return $type;
	}
}