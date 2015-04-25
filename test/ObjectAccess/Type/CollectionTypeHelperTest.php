<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Query\Scope;
use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\Origin_ElementInCollection;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;
use Light\ObjectAccess\Resource\ResolvedCollectionValue;
use Light\ObjectAccess\Resource\ResolvedObject;
use Light\ObjectAccess\Resource\ResolvedValue;
use Light\ObjectAccess\Resource\Util\EmptyResourceAddress;
use Light\ObjectAccess\TestData\Post;
use Light\ObjectAccess\TestData\Setup;
use Light\ObjectAccess\Transaction\Util\DummyTransaction;

include_once("test/ObjectAccess/TestData/Setup.php");

class CollectionTypeHelperTest extends \PHPUnit_Framework_TestCase
{
	/** @var Setup */
	private $setup;

	protected function setUp()
	{
		parent::setUp();

		$this->setup = Setup::create();
	}

	public function testGetBaseTypeHelper()
	{
		$helper = $this->setup->getTypeRegistry()->getCollectionTypeHelper(Post::class . "[]");
		$baseHelper = $helper->getBaseTypeHelper();

		$this->assertInstanceOf(ComplexTypeHelper::class, $baseHelper);
		$this->assertEquals(Post::class, $baseHelper->getName());
	}

	public function testGetElementAtKey()
	{
		$helper = $this->setup->getTypeRegistry()->getCollectionTypeHelper(Post::class . "[]");

		$coll = new ResolvedCollectionResource($helper, EmptyResourceAddress::create(), Origin::unavailable());

		$result = $helper->getElementAtKey($coll, 4040);
		$this->assertInstanceOf(ResolvedObject::class, $result);
		$this->assertSame($this->setup->getDatabase()->getPost(4040), $result->getValue());

		$this->assertNull($helper->getElementAtKey($coll, 1234));
	}

	public function testAppendingWithNoOrigin()
	{
		$helper = $this->setup->getTypeRegistry()->getCollectionTypeHelper(Post::class . "[]");

		$coll = new ResolvedCollectionResource($helper, EmptyResourceAddress::create(), Origin::unavailable());

		$post = new Post();
		$post->setId(5050);
		$helper->appendValue($coll, $post, $tx = new DummyTransaction());

		$this->assertSame($post, $this->setup->getDatabase()->getPost(5050));

		// Check the transaction
		$this->assertContains($coll, $tx->getChangedResources());
		$this->assertEquals(1, count($tx->getChangedResources()));
	}

	public function testIterate()
	{
		$helper = $this->setup->getTypeRegistry()->getCollectionTypeHelper(Post::class . "[]");

		$coll = new ResolvedCollectionResource($helper, EmptyResourceAddress::create(), Origin::unavailable());

		$iterator = $helper->getIterator($coll);
		$this->assertInstanceOf(\Iterator::class, $iterator);

		$iterator->rewind();
		$result = $iterator->current();
		$this->assertInstanceOf(ResolvedObject::class, $result);
		$this->assertSame($this->setup->getDatabase()->getPost(4040), $result->getValue());
		$this->assertEquals(4040, $iterator->key());
		$this->assertInstanceOf(Origin_ElementInCollection::class, $result->getOrigin());
	}

	public function testRead()
	{
		$helper = $this->setup->getTypeRegistry()->getCollectionTypeHelper(Post::class . "[]");
		$coll = new ResolvedCollectionResource($helper, EmptyResourceAddress::create(),Origin::unavailable());

		$valuesColl = $helper->readCollection($coll);
		$this->assertInstanceOf(ResolvedCollectionValue::class, $valuesColl);
		$this->assertEquals($this->setup->getDatabase()->getPosts(), $valuesColl->getValue());
		$this->assertSame($coll->getOrigin(), $valuesColl->getOrigin());
	}

	public function testGetIteratorWithScope()
	{
		$helper = $this->setup->getTypeRegistry()->getCollectionTypeHelper(Post::class . "[]");

		$coll = new ResolvedCollectionResource($helper, EmptyResourceAddress::create(), Origin::unavailable());

		$iterator = $helper->getIteratorWithScope($coll, Scope::createWithKey(4040));
		$elements = iterator_to_array($iterator);
		$this->assertEquals(1, count($elements));
		$this->assertSame($this->setup->getDatabase()->getPost(4040), $elements[4040]->getValue());
	}

	public function testApplyScope()
	{
		$helper = $this->setup->getTypeRegistry()->getCollectionTypeHelper(Post::class . "[]");
		$coll = new ResolvedCollectionResource($helper, EmptyResourceAddress::create(), Origin::unavailable());

		$result = $helper->applyScope($coll, Scope::createWithKey(4040));
		$this->assertInstanceOf(ResolvedObject::class, $result);
		$this->assertSame($this->setup->getDatabase()->getPost(4040), $result->getValue());

		$result = $helper->applyScope($coll, Scope::createEmptyScope());
		$this->assertInstanceOf(ResolvedCollectionValue::class, $result);
		$this->assertEquals(4, count($result->getValue()));
	}
}
