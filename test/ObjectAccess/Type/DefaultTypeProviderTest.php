<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Type\Util\BuiltinSimpleType;
use Light\ObjectAccess\Type\Util\DefaultTypeProvider;

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
}
