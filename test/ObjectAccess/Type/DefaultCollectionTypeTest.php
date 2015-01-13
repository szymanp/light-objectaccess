<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\TestData\Post;
use Light\ObjectAccess\TestData\Setup;

include_once("test/ObjectAccess/TestData/Setup.php");

class DefaultCollectionTypeTest extends \PHPUnit_Framework_TestCase
{
	/** @var TypeRegistry */
	private $typeRegistry;

	protected function setUp()
	{
		parent::setUp();

		$this->typeRegistry = Setup::getTypeRegistry();
	}

	public function testIsValidValue()
	{
		$type = $this->typeRegistry->getCollectionTypeHelper(Post::class . "[]")->getType();

		$this->assertFalse($type->isValidValue($this->typeRegistry, "abc"));
		$this->assertTrue($type->isValidValue($this->typeRegistry, array()));
		$this->assertTrue($type->isValidValue($this->typeRegistry, array(new Post())));

		$arrayObject = new \ArrayObject(array(new Post()));
		$this->assertTrue($type->isValidValue($this->typeRegistry, $arrayObject));
	}

}
