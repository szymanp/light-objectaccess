<?php
namespace Light\ObjectAccess\TestData;

use Light\ObjectAccess\Type\TypeRegistry;
use Light\ObjectAccess\Type\Util\DefaultCollectionType;
use Light\ObjectAccess\Type\Util\DefaultComplexType;
use Light\ObjectAccess\Type\Util\DefaultTypeProvider;

include_once("Author.php");
include_once("Post.php");
include_once("Database.php");

class Setup
{
	/**
	 * @return TypeRegistry
	 */
	public static function getTypeRegistry()
	{
		$provider = new DefaultTypeProvider();
		$provider->addType(Author::createType());
		$provider->addType(Post::createType());
		$provider->addType(new DefaultCollectionType("Post"));

		return new TypeRegistry($provider);
	}
}