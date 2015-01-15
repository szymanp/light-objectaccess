<?php
namespace Light\ObjectAccess\TestData;

use Light\ObjectAccess\Resource\Origin_Unavailable;
use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;
use Light\ObjectAccess\Type\Collection\Append;
use Light\ObjectAccess\Type\Collection\Element;
use Light\ObjectAccess\Type\Util\DefaultCollectionType;

class PostCollectionType extends DefaultCollectionType implements Append
{
	/** @var Database */
	private $database;

	public function __construct(Database $db)
	{
		parent::__construct(Post::class);

		$this->database = $db;
	}

	/**
	 * Appends a value to the collection
	 * @param ResolvedCollection $collection
	 * @param mixed              $value
	 */
	public function appendValue(ResolvedCollection $collection, $value)
	{
		// TODO: Implement appendValue() method.
	}

	protected function getElementAtKeyFromResource(ResolvedCollectionResource $coll, $key)
	{
		if ($coll->getOrigin() instanceof Origin_Unavailable)
		{
			$value = $this->database->getPost($key);
			if (is_null($value))
			{
				return Element::notExists();
			}
			else
			{
				return Element::valueOf($value);
			}
		}
	}

}