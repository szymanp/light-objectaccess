<?php
namespace Light\ObjectAccess\Resource;

use Light\ObjectAccess\Query\Scope;
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

	public function testScopeInPath()
	{
		$author = $this->setup->getDatabase()->getAuthor(1010);
		$authorResource = $this->setup->getTypeRegistry()->resolveValue($author);

		// author/posts
		$addr = new DefaultRelativeAddress($authorResource);
		$addr->appendElement("posts");

		$reader = new RelativeAddressReader($addr);
		$result = $reader->read();

		$this->assertInstanceOf(ResolvedCollectionResource::class, $result);

		// author/posts/
		$addr->appendScope(Scope::createEmptyScope());

		$reader = new RelativeAddressReader($addr);
		$result = $reader->read();

		$this->assertInstanceOf(ResolvedCollectionValue::class, $result);
		$this->assertEquals($this->setup->getDatabase()->getPostsForAuthor($author), $result->getValue());
	}

	/**
	 * @expectedException 			\Light\ObjectAccess\Exception\AddressResolutionException
	 * @expectedExceptionMessage 	A scope cannot be applied to a collection of values
	 */
	public function testDoubleScopeInPath()
	{
		$author = $this->setup->getDatabase()->getAuthor(1010);
		$authorResource = $this->setup->getTypeRegistry()->resolveValue($author);

		$addr = new DefaultRelativeAddress($authorResource);
		$addr->appendElement("posts");
		$addr->appendScope(Scope::createEmptyScope());
		$addr->appendScope(Scope::createEmptyScope());

		$reader = new RelativeAddressReader($addr);
		$reader->read();
	}

	public function testTraceOnReadElementFromCollectionProperty()
	{
		$author = $this->setup->getDatabase()->getAuthor(1020);
		$authorResource = $this->setup->getTypeRegistry()->resolveValue($author);

		$addr = new DefaultRelativeAddress($authorResource);
		$addr->appendElement("posts");
		$addr->appendIndex(0);

		$reader = new RelativeAddressReader($addr);
		$result = $reader->read();

		$trace = $reader->getLastResolutionTrace();
		$this->assertInstanceOf(ResolutionTrace::class, $trace);
		$this->assertEquals(2, count($trace));
		$this->assertTrue($trace->isFinalized());

		$list = iterator_to_array($trace->getIterator());
		$this->assertEquals($list[0]->getPathElement(), "posts");
		$this->assertEquals($list[1]->getPathElement(), 0);
		$this->assertSame($list[1]->previous(), $list[0]);
		$this->assertNull($list[0]->previous());

		$this->assertInstanceOf(ResolvedCollectionResource::class, $list[0]->getResource());
		$this->assertSame($list[1]->getResource(), $result);

		$this->assertSame($list[0], $trace->first());
		$this->assertSame($list[1], $trace->last());
	}

}
