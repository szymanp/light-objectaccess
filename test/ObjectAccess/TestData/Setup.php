<?php
namespace Light\ObjectAccess\TestData;

use Light\ObjectAccess\Resource\Origin_Unavailable;
use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;
use Light\ObjectAccess\Type\Collection\Element;
use Light\ObjectAccess\Type\TypeRegistry;
use Light\ObjectAccess\Type\Util\DefaultCollectionType;
use Light\ObjectAccess\Type\Util\DefaultTypeProvider;

include_once("Author.php");
include_once("PostType.php");
include_once("Post.php");
include_once("Database.php");
include_once("PostCollectionType.php");

class Setup
{
	/** @var Database */
	private $database;
	/** @var TypeRegistry */
	private $typeRegistry;

	/**
	 * Returns a new Setup instance.
	 * @return Setup
	 */
	public static function create()
	{
		return new self;
	}

	private function __construct()
	{
		$this->database = new Database();
		$this->typeRegistry = $this->createTypeRegistry();
	}

	/**
	 * @return Database
	 */
	public function getDatabase()
	{
		return $this->database;
	}

	/**
	 * @return TypeRegistry
	 */
	public function getTypeRegistry()
	{
		return $this->typeRegistry;
	}

	/**
	 * @return TypeRegistry
	 */
	private function createTypeRegistry()
	{
		$provider = new DefaultTypeProvider();
		$provider->addType(Author::createType());
		$provider->addType(Post::createType());
		$provider->addType(new PostCollectionType($this->database));

		return new TypeRegistry($provider);
	}
}