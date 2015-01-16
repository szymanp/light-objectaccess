<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\TestData\Post;
use Light\ObjectAccess\TestData\Setup;
use Light\ObjectAccess\Type\Collection\Property;

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
}