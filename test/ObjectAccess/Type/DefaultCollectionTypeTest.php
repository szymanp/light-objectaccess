<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\ResolvedCollectionValue;
use Light\ObjectAccess\Resource\Util\EmptyResourceAddress;
use Light\ObjectAccess\TestData\Post;
use Light\ObjectAccess\TestData\Setup;
use Light\ObjectAccess\Type\Collection\Element;

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

	public function testGetElementAtOffsetFromArray()
	{
		$typeHelper = $this->typeRegistry->getCollectionTypeHelper(Post::class . "[]");
		$type = $typeHelper->getType();

		$array = array(new Post(), new Post());
		$resolvedCollectionValue = new ResolvedCollectionValue($typeHelper, $array, EmptyResourceAddress::create(), Origin::unavailable());

		$value = $type->getElementAtOffset($resolvedCollectionValue, 0);
		$this->assertInstanceOf(Element::class, $value);
		$this->assertTrue($value->exists());
		$this->assertSame($array[0], $value->getValue());

		$value = $type->getElementAtOffset($resolvedCollectionValue, 3);
		$this->assertInstanceOf(Element::class, $value);
		$this->assertFalse($value->exists());
	}

}
