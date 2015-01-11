<?php
namespace Light\ObjectAccess\Type;

use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\ResolvedScalar;
use Light\ObjectAccess\Resource\ResolvedValue;
use Light\ObjectAccess\Resource\Util\EmptyResourceAddress;
use Light\ObjectAccess\TestData\Author;
use Light\ObjectAccess\TestData\Database;
use Light\ObjectAccess\TestData\Setup;

include_once("test/ObjectAccess/TestData/Setup.php");

class ComplexTypeHelperTest extends \PHPUnit_Framework_TestCase
{
	public function testGetName()
	{
		$typeHelper = Setup::getTypeRegistry()->getTypeHelperByName(Author::class);

		$this->assertEquals(Author::class, $typeHelper->getName());
	}

	public function testReadProperty()
	{
		$typeHelper = Setup::getTypeRegistry()->getTypeHelperByName(Author::class);

		$database = new Database();
		$author = $database->getAnyAuthor();
		$resolvedAuthor = ResolvedValue::create($typeHelper, $author, EmptyResourceAddress::create(), Origin::unavailable());

		$resolvedId = $typeHelper->readProperty($resolvedAuthor, "id");
		$this->assertInstanceOf(ResolvedScalar::class, $resolvedId);
		$this->assertEquals("int", $resolvedId->getType()->getPhpType());
	}
}
