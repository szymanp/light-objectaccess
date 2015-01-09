<?php
namespace Light\ObjectAccess\Type;

use Light\Exception\NotImplementedException;
use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\ResolvedValue;
use Light\ObjectAccess\Resource\Util\EmptyResourceAddress;
use Light\ObjectAccess\Transaction\Util\DummyTransaction;
use Light\ObjectAccess\Type\Util\DefaultComplexType;
use Light\ObjectAccess\Type\Util\DefaultProperty;

class DefaultPropertyTest extends \PHPUnit_Framework_TestCase
{
	/** @var ComplexType */
	private $type;

	protected function setUp()
	{
		parent::setUp();

		$this->type = $type = new DefaultComplexType("Light\ObjectAccess\Type\DefaultPropertyTest_TestObject");
		$type->addProperty(new DefaultProperty("name"));
		$type->addProperty(new DefaultProperty("surname"));
		$type->addProperty(new DefaultProperty("age"));
	}

	public function testReadingAndWriting()
	{
		$ob = new DefaultPropertyTest_TestObject();
		$ro = ResolvedValue::create(new DefaultPropertyTest_TypeHelper, $ob, EmptyResourceAddress::create(), Origin::unavailable());

		$this->assertEquals($ob->getName(), $this->type->getProperty("name")->readProperty($ro));
		$this->assertEquals($ob->getSurname(), $this->type->getProperty("surname")->readProperty($ro));
		$this->assertEquals($ob->age, $this->type->getProperty("age")->readProperty($ro));

		$this->type->getProperty("name")->writeProperty($ro, "Max", new DummyTransaction());
		$this->assertEquals("Max", $ob->getName());
	}

	/**
	 * @expectedException Light\ObjectAccess\Exception\ResourceException
	 */
	public function testMissingProperty()
	{
		$ob = new DefaultPropertyTest_TestObject();
		$ro = ResolvedValue::create(new DefaultPropertyTest_TypeHelper, $ob, EmptyResourceAddress::create(), Origin::unavailable());
		$prop = new DefaultProperty("missing");

		$prop->readProperty($ro);
	}

}

class DefaultPropertyTest_TypeHelper extends ComplexTypeHelper
{
	public function __construct()
	{
	}

	public function getType()
	{
		throw new NotImplementedException;
	}

	public function getAddress()
	{
		throw new NotImplementedException;
	}

	public function getName()
	{
		throw new NotImplementedException;
	}
}

class DefaultPropertyTest_TestObject
{
	private $name = "John";
	private $surname = "Doe";
	public $age = 35;

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getSurname()
	{
		return $this->surname;
	}

	/**
	 * @param string $surname
	 */
	public function setSurname($surname)
	{
		$this->surname = $surname;
	}
}