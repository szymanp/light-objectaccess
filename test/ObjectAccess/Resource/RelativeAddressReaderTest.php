<?php
namespace Light\ObjectAccess\Resource;

use Light\ObjectAccess\Resource\Util\DefaultRelativeAddress;
use Light\ObjectAccess\TestData\Setup;

include_once("test/ObjectAccess/TestData/Setup.php");

class RelativeAddressReaderTest extends \PHPUnit_Framework_TestCase
{
	/** @var Setup */
	private $setup;

	protected function setUp()
	{
		parent::setUp();
		$this->setup = Setup::create();
	}

	public function testReadStringProperty()
	{
		$author = $this->setup->getDatabase()->getAuthor(1020);
		$authorResource = $this->setup->getTypeRegistry()->resolveValue($author);

		$addr = new DefaultRelativeAddress($authorResource);
		$addr->appendElement("name");

		$reader = new RelativeAddressReader($addr);
		$result = $reader->read();

		$this->assertInstanceOf(ResolvedScalar::class, $result);
		$this->assertEquals($author->name, $result->getValue());
	}

	public function testReadElementFromCollectionProperty()
	{
		$author = $this->setup->getDatabase()->getAuthor(1020);
		$authorResource = $this->setup->getTypeRegistry()->resolveValue($author);

		$addr = new DefaultRelativeAddress($authorResource);
		$addr->appendElement("posts");
		$addr->appendIndex(0);

		$reader = new RelativeAddressReader($addr);
		$result = $reader->read();

		$this->assertInstanceOf(ResolvedObject::class, $result);
		$this->assertEquals(4042, $result->getValue()->getId());

		$addr = new DefaultRelativeAddress($authorResource);
		$addr->appendElement("posts");
		$addr->appendIndex(0);
		$addr->appendElement("title");

		$reader = new RelativeAddressReader($addr);
		$result = $reader->read();

		$this->assertInstanceOf(ResolvedScalar::class, $result);
		$this->assertEquals("Is this working?", $result->getValue());

	}
}
