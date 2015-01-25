<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\TestData\Author;
use Light\ObjectAccess\Type\Util\BuiltinSimpleType;
use Light\ObjectAccess\Type\Util\DefaultNameProvider;

include_once("test/ObjectAccess/TestData/Author.php");

class DefaultNameProviderTest extends \PHPUnit_Framework_TestCase
{
	public function testSimpleType()
	{
		$stringType = new BuiltinSimpleType("string");

		$nameProvider = new DefaultNameProvider();
		$this->assertEquals("string", $nameProvider->getTypeName($stringType));
		$this->assertEquals("php:string", $nameProvider->getTypeUri($stringType));
	}

	public function testPrefix()
	{
		$authorType = Author::createType();

		$nameProvider = new DefaultNameProvider();
		$nameProvider->setPrefix("Light\ObjectAccess\TestData");
		$this->assertEquals("Author", $nameProvider->getTypeName($authorType));
	}

	public function testGetNameForUri()
	{
		$nameProvider = new DefaultNameProvider();
		$this->assertEquals("string", $nameProvider->getNameFromUri("php:string"));
		$this->assertNull($nameProvider->getNameFromUri("http://somewhere.example.org/"));
	}

}
