<?php
namespace Light\ObjectAccess\Query;

use Light\ObjectAccess\Exception\TypeException;
use Light\ObjectAccess\Query\Argument\Criterion;
use Light\ObjectAccess\TestData\Author;
use Light\ObjectAccess\TestData\Post;
use Light\ObjectAccess\TestData\Setup;
use Light\ObjectAccess\Type\CollectionTypeHelper;

include_once("test/ObjectAccess/TestData/Setup.php");

class QueryConcreteTest extends \PHPUnit_Framework_TestCase
{
	/** @var CollectionTypeHelper */
	private $postCollectionTypeHelper;
	/** @var Setup */
	private $setup;

	protected function setUp()
	{
		parent::setUp();
		$this->setup = Setup::create();

		$this->postCollectionTypeHelper = $this->setup->getTypeRegistry()->getCollectionTypeHelper(Post::class . "[]");
	}

	/**
	 * @expectedException 			Light\ObjectAccess\Exception\TypeException
	 * @expectedExceptionMessage	Value 123 is not valid for property Light\ObjectAccess\TestData\Author::author
	 */
	public function testInvalidPropertyValue()
	{
		$query = Query::create($this->postCollectionTypeHelper);
		$query->append("author", new Criterion(123));
	}

	public function testAddRetrieveProperty()
	{
		$query = Query::create($this->postCollectionTypeHelper);
		$query->append("author", $c = new Criterion(new Author()));

		$proplist = $query->getArgumentList("author");
		$this->assertFalse($proplist->isEmpty());
		$iterator = $proplist->getIterator();
		$iterator->rewind();
		$this->assertSame($c, $iterator->current());
	}
}
