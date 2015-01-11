<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\TestData\Author;
use Light\ObjectAccess\Type\Util\BuiltinSimpleType;
use Light\ObjectAccess\Type\Util\DefaultTypeProvider;

include_once("test/ObjectAccess/TestData/Author.php");

class DefaultTypeProviderTest extends \PHPUnit_Framework_TestCase
{
	private $stringType;

	protected function setUp()
	{
		parent::setUp();
		$this->stringType = new BuiltinSimpleType("string");
	}

	public function testAddAndRetrieve()
	{
		$provider = new DefaultTypeProvider();
		$provider->addType($this->stringType);

		$this->assertSame($this->stringType, $provider->getTypeByName("string"));
		$this->assertEquals("string", $provider->getTypeName($this->stringType));
		$this->assertEquals("php:string", $provider->getTypeUri($this->stringType));
	}

	public function testGetSimpleTypeByValue()
	{
		$provider = new DefaultTypeProvider();

		$type = $provider->getTypeByValue("hello");
		$this->assertInstanceOf(SimpleType::class, $type);
		$this->assertEquals("string", $type->getPhpType());

		$type = $provider->getTypeByValue(123);
		$this->assertInstanceOf(SimpleType::class, $type);
		$this->assertEquals("integer", $type->getPhpType());
	}

	public function testGetComplexTypeByValue()
	{
		$provider = new DefaultTypeProvider();
		$provider->addType(Author::createType());

		$author = new Author();

		$type = $provider->getTypeByValue($author);
		$this->assertInstanceOf(ComplexType::class, $type);
		$this->assertEquals(Author::class, $type->getClassName());
	}
}
