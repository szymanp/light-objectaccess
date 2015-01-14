<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;
use Light\ObjectAccess\Resource\ResolvedObject;
use Light\ObjectAccess\Resource\ResolvedValue;
use Light\ObjectAccess\Resource\Util\EmptyResourceAddress;
use Light\ObjectAccess\TestData\Post;
use Light\ObjectAccess\TestData\Setup;

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
}
