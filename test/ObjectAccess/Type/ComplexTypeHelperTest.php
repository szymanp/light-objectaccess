<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\Origin_PropertyOfObject;
use Light\ObjectAccess\Resource\Origin_Unavailable;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;
use Light\ObjectAccess\Resource\ResolvedNull;
use Light\ObjectAccess\Resource\ResolvedObject;
use Light\ObjectAccess\Resource\ResolvedScalar;
use Light\ObjectAccess\Resource\ResolvedValue;
use Light\ObjectAccess\Resource\Util\EmptyResourceAddress;
use Light\ObjectAccess\TestData\Author;
use Light\ObjectAccess\TestData\Database;
use Light\ObjectAccess\TestData\DummyAddress;
use Light\ObjectAccess\TestData\Post;
use Light\ObjectAccess\TestData\PostType;
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

	public function testGetPropertyTypeHelper()
	{
		$typeHelper = $this->typeRegistry->getTypeHelperByName(Author::class);
		$propertyTypeHelper = $typeHelper->getPropertyTypeHelper("id");
		$this->assertInstanceOf(SimpleType::class, $propertyTypeHelper->getType());
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

		// Check the transaction
		$this->assertContains($resolved, $transaction->getChangedResources());
		$this->assertEquals(1, count($transaction->getChangedResources()));
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

	public function testCreate()
	{
		$typeHelper = $this->typeRegistry->getComplexTypeHelper(Post::class);
		$resource = $typeHelper->createResource($tx = new DummyTransaction());

		$this->assertInstanceOf(ResolvedObject::class, $resource);
		$this->assertInstanceOf(DummyAddress::class, $resource->getAddress());
		$this->assertEquals("//post/" . $resource->getValue()->getId(), $resource->getAddress()->getAsString());
		$this->assertInstanceOf(Origin_Unavailable::class, $resource->getOrigin());

		$post = $resource->getValue();
		$this->assertInstanceOf(Post::class, $post);
		$this->assertEquals(PostType::$autoId-1, $post->getId());
		// Check the transaction
		$this->assertContains($resource, $tx->getCreatedResources());
		$this->assertEquals(1, count($tx->getCreatedResources()));
	}

	public function testReadNullProperty()
	{
		$typeHelper = $this->typeRegistry->getComplexTypeHelper(Post::class);
		$collectionHelper = $this->typeRegistry->getCollectionTypeHelper(Post::class . "[]");

		$database = $this->setup->getDatabase();
		$post = $database->getPost(4043);
		$resolvedPost = ResolvedValue::create($typeHelper, $post, EmptyResourceAddress::create(), Origin::unavailable());

		// There is no Author object assigned - the value of resolvedAuthor is NULL.
		$resolvedAuthor = $typeHelper->readProperty($resolvedPost, "author");
		$this->assertNotNull($resolvedAuthor);
		$this->assertInstanceOf(ResolvedNull::class, $resolvedAuthor);

		$this->assertInstanceOf(Origin_PropertyOfObject::class, $resolvedAuthor->getOrigin());
		$this->assertSame($resolvedAuthor->getOrigin()->getObject(), $resolvedPost);
		$this->assertEquals($resolvedAuthor->getOrigin()->getPropertyName(), "author");
	}

}
