<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\Origin_PropertyOfObject;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;
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
	/** @var Setup */
	private $setup;
	/** @var TypeRegistry */
	private $typeRegistry;

	protected function setUp()
	{
		parent::setUp();

		$this->setup = Setup::create();
		$this->typeRegistry = $this->setup->getTypeRegistry();
	}

	public function testGetName()
	{
		$typeHelper = $this->typeRegistry->getTypeHelperByName(Author::class);

		$this->assertEquals(Author::class, $typeHelper->getName());
	}

	public function testGetPropertyType()
	{
		$typeHelper = $this->typeRegistry->getTypeHelperByName(Author::class);
		$property = $typeHelper->getPropertyType("id");
		$this->assertInstanceOf(SimpleType::class, $property);
	}

	public function testReadProperty()
	{
		$typeHelper = $this->typeRegistry->getComplexTypeHelper(Author::class);

		$database = $this->setup->getDatabase();
		$author = $database->getAnyAuthor();
		$resolvedAuthor = ResolvedValue::create($typeHelper, $author, EmptyResourceAddress::create(), Origin::unavailable());

		$resolvedId = $typeHelper->readProperty($resolvedAuthor, "id");
		$this->assertInstanceOf(ResolvedScalar::class, $resolvedId);
		$this->assertEquals("int", $resolvedId->getType()->getPhpType());
	}

	public function testWriteProperty()
	{
		$database = $this->setup->getDatabase();
		$transaction = new DummyTransaction();

		$author = $database->getAnyAuthor();
		$resolved = $this->typeRegistry->resolveValue($author);

		$this->assertNotEquals("James Bond", $author->name);
		$resolved->getTypeHelper()->writeProperty($resolved, "name", "James Bond", $transaction);
		$this->assertEquals("James Bond", $author->name);
	}

	public function testReadCollectionResourceProperty()
	{
		$typeHelper = $this->typeRegistry->getComplexTypeHelper(Author::class);

		$database = $this->setup->getDatabase();
		$author = $database->getAnyAuthor();
		$resolvedAuthor = ResolvedValue::create($typeHelper, $author, EmptyResourceAddress::create(), Origin::unavailable());

		$resolvedPosts = $typeHelper->readProperty($resolvedAuthor, "posts");
		$this->assertInstanceOf(ResolvedCollectionResource::class, $resolvedPosts);

		$origin = $resolvedPosts->getOrigin();
		$this->assertInstanceOf(Origin_PropertyOfObject::class, $origin);

		$this->assertSame($resolvedAuthor, $origin->getObject());
		$this->assertEquals("posts", $origin->getPropertyName());
	}
}
