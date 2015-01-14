<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Resource\ResolvedScalar;
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
		$this->assertInstanceOf(SimpleTypeHelper::class, $stringType);
		$this->assertEquals("string", $stringType->getType()->getPhpType());
	}

	/**
	 * @expectedException 			\Light\ObjectAccess\Exception\TypeException
	 * @expectedExceptionMessage	No type with name "Unknown" is known by this TypeRegistry
	 */
	public function testInvalidGetTypeHelperByName()
	{
		$this->registry->getTypeHelperByName("Unknown");
	}

	public function testResolveValue()
	{
		$resolved = $this->registry->resolveValue("hello world");
		$this->assertInstanceOf(ResolvedScalar::class, $resolved);
		$this->assertEquals("hello world", $resolved->getValue());
	}
}
