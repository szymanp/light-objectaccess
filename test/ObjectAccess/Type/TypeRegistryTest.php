<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Type\Util\DefaultTypeProvider;

class TypeRegistryTest extends \PHPUnit_Framework_TestCase
{
	/** @var TypeRegistry */
	private $registry;

	protected function setUp()
	{
		parent::setUp();

		$provider = new DefaultTypeProvider();
		$this->registry = new TypeRegistry($provider);
	}

	public function testGetTypeHelperByName()
	{
		$stringType = $this->registry->getTypeHelperByName("string");
		$this->assertInstanceOf(SimpleTypeHelper::CLASSNAME, $stringType);
		$this->assertEquals("string", $stringType->getType()->getPhpType());
	}
}
