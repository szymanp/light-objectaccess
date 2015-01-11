<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\ResolvedScalar;
use Light\ObjectAccess\Resource\ResolvedValue;
use Light\ObjectAccess\Resource\Util\EmptyResourceAddress;
use Light\ObjectAccess\TestData\Author;
use Light\ObjectAccess\TestData\Database;
use Light\ObjectAccess\TestData\Setup;
use Light\ObjectAccess\Transaction\Util\DummyTransaction;

include_once("test/ObjectAccess/TestData/Setup.php");

class ComplexTypeHelperTest extends \PHPUnit_Framework_TestCase
{
	/** @var TypeRegistry */
	private $typeRegistry;

	protected function setUp()
	{
		parent::setUp();

		$this->typeRegistry = Setup::getTypeRegistry();
	}

	public function testGetName()
	{
		$typeHelper = $this->typeRegistry->getTypeHelperByName(Author::class);

		$this->assertEquals(Author::class, $typeHelper->getName());
	}

	public function testReadProperty()
	{
		$typeHelper = $this->typeRegistry->getTypeHelperByName(Author::class);

		$database = new Database();
		$author = $database->getAnyAuthor();
		$resolvedAuthor = ResolvedValue::create($typeHelper, $author, EmptyResourceAddress::create(), Origin::unavailable());

		$resolvedId = $typeHelper->readProperty($resolvedAuthor, "id");
		$this->assertInstanceOf(ResolvedScalar::class, $resolvedId);
		$this->assertEquals("int", $resolvedId->getType()->getPhpType());
	}

	public function testWriteProperty()
	{
		$database = new Database();
		$transaction = new DummyTransaction();

		$author = $database->getAnyAuthor();
		$resolved = $this->typeRegistry->resolveValue($author);

		$this->assertNotEquals("James Bond", $author->name);
		$resolved->getTypeHelper()->writeProperty($resolved, "name", "James Bond", $transaction);
		$this->assertEquals("James Bond", $author->name);
	}
}
