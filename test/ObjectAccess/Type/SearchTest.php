<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Query\Argument\Criterion;
use Light\ObjectAccess\Query\Query;
use Light\ObjectAccess\Query\Scope;
use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\ResolvedValue;
use Light\ObjectAccess\Resource\Util\EmptyResourceAddress;
use Light\ObjectAccess\TestData\Post;
use Light\ObjectAccess\TestData\Setup;
use Light\ObjectAccess\Type\Collection\Property;
use Light\ObjectAccess\Type\Util\EmptySearchContext;

include_once("test/ObjectAccess/TestData/Setup.php");

class SearchTest extends \PHPUnit_Framework_TestCase
{
	/** @var Setup */
	private $setup;

	protected function setUp()
	{
		parent::setUp();
		$this->setup = Setup::create();
	}

	public function testReadCollectionProperty()
	{
		$typeHelper = $this->setup->getTypeRegistry()->getTypeHelperByName(Post::class . "[]");

		$type = $typeHelper->getType();
		$this->assertInstanceOf(Property::class, $type->getProperty("author"));
		$this->assertNull($type->getProperty("notExists"));
	}

	public function testFindAllPostsWithAuthor()
	{
		$typeHelper = $this->setup->getTypeRegistry()->getCollectionTypeHelper(Post::class . "[]");
		$db = $this->setup->getDatabase();

		$collection = ResolvedValue::create($typeHelper, $db->getPosts(), EmptyResourceAddress::create(), Origin::unavailable());

		$query = Query::create($typeHelper);
		$query->append("author", new Criterion($db->getAuthor(1010)));
		$scope = Scope::createWithQuery($query);

		$iterator = $typeHelper->findElements($collection, $scope, EmptySearchContext::create());
		$elements = iterator_to_array($iterator);

		$this->assertEquals(2, count($elements));
		$this->assertSame($db->getPost(4040), $elements[4040]->getValue());
		$this->assertSame($db->getPost(4041), $elements[4041]->getValue());
	}
}