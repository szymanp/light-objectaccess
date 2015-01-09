<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Type\Util\BuiltinSimpleType;
use Light\ObjectAccess\Type\Util\DefaultNameProvider;

class DefaultNameProviderTest extends \PHPUnit_Framework_TestCase
{
	public function testSimpleType()
	{
		$stringType = new BuiltinSimpleType("string");

		$nameProvider = new DefaultNameProvider();
		$this->assertEquals("string", $nameProvider->getTypeName($stringType));
		$this->assertEquals("php:string", $nameProvider->getTypeUri($stringType));
	}
}
