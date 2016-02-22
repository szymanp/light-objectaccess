<?php
namespace Light\ObjectAccess\TestData;

use Szyman\Exception\InvalidArgumentException;
use Light\ObjectAccess\Resource\Addressing\ResourceAddress;
use Light\ObjectAccess\Transaction\Transaction;
use Light\ObjectAccess\Type\Complex\CanonicalAddress;
use Light\ObjectAccess\Type\Complex\Create;
use Light\ObjectAccess\Type\Util\DefaultComplexType;
use Light\ObjectAccess\Type\Util\DefaultProperty;

class PostType extends DefaultComplexType implements Create, CanonicalAddress
{
	public static $autoId = 9090;

	public function __construct()
	{
		parent::__construct("Light\ObjectAccess\TestData\Post");
		$this->addProperty(new DefaultProperty("id"));
		$this->addProperty(new DefaultProperty("title", "string"));
		$this->addProperty(new DefaultProperty("text"));
		$this->addProperty(new DefaultProperty("author", Author::class));
	}

	/**
	 * Creates a new instance of an object of this complex type.
	 * @param Transaction $transaction
	 * @return object
	 */
	public function createObject(Transaction $transaction)
	{
		$newPost = new Post();
		$newPost->setId(self::$autoId++);
		return $newPost;
	}

	/**
	 * Returns a canonical address for the specified object.
	 * @param mixed $object
	 * @return ResourceAddress
	 */
	public function getCanonicalAddress($object)
	{
		if ($object instanceof Post)
		{
			return new DummyAddress("//post/" . $object->getId());
		}
		else
		{
			throw InvalidArgumentException::newInvalidType('$object', $object, Post::class);
		}
	}
}