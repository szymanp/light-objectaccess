<?php
namespace Light\ObjectAccess\TestData;

use Light\Exception\Exception;
use Light\Exception\NotImplementedException;
use Light\ObjectAccess\Query\Scope;
use Light\ObjectAccess\Query\Scope\Scope_Query;
use Light\ObjectAccess\Resource\Origin_PropertyOfObject;
use Light\ObjectAccess\Resource\Origin_Unavailable;
use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;
use Light\ObjectAccess\Resource\ResolvedCollectionValue;
use Light\ObjectAccess\Transaction\Transaction;
use Light\ObjectAccess\Type\Collection\Append;
use Light\ObjectAccess\Type\Collection\Element;
use Light\ObjectAccess\Type\Collection\Iterate;
use Light\ObjectAccess\Type\Collection\Property;
use Light\ObjectAccess\Type\Collection\Search;
use Light\ObjectAccess\Type\Collection\SearchContext;
use Light\ObjectAccess\Type\Util\CollectionPropertyHost;
use Light\ObjectAccess\Type\Util\DefaultCollectionType;
use Light\ObjectAccess\Type\Util\DefaultFilterableProperty;

class PostCollectionType extends DefaultCollectionType implements Append, Iterate, Search
{
	/** @var Database */
	private $database;
	/** @var CollectionPropertyHost */
	private $properties;

	public function __construct(Database $db)
	{
		parent::__construct(Post::class);

		$this->database = $db;
		$this->properties = new CollectionPropertyHost();
		$this->properties->append(new DefaultFilterableProperty("author", Author::class));
	}

	/**
	 * Appends a value to the collection
	 * @param ResolvedCollection $collection
	 * @param mixed              $value
	 * @param Transaction		 $transaction
	 */
	public function appendValue(ResolvedCollection $collection, $value, Transaction $transaction)
	{
		if ($collection->getOrigin() instanceof Origin_Unavailable)
		{
			$this->database->addPost($value);
		}
		else
		{
			throw new NotImplementedException();
		}
	}

	protected function getElementAtKeyFromResource(ResolvedCollectionResource $coll, $key)
	{
		$origin = $coll->getOrigin();

		if ($origin instanceof Origin_Unavailable)
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
		elseif ($origin instanceof Origin_PropertyOfObject)
		{
			$object = $origin->getObject()->getValue();
			if ($object instanceof Author && $origin->getPropertyName() == "posts")
			{
				$posts = $this->database->getPostsForAuthor($object);
				if (isset($posts[$key]))
				{
					return Element::valueOf($posts[$key]);
				}
				else
				{
					return Element::notExists();
				}
			}
			else
			{
				throw new Exception("PostCollectionType only supports Author objects, not %1::%2",
									get_class($origin->getObject()),
									$origin->getPropertyName());
			}
		}
		else
		{
			throw new NotImplementedException("Origin is " . get_class($origin));
		}
	}

	/**
	 * Returns an Iterator over the elements in the given collection.
	 * @param ResolvedCollection $collection
	 * @return \Iterator
	 * @throws NotImplementedException
	 */
	public function getIterator(ResolvedCollection $collection)
	{
		if ($collection instanceof ResolvedCollectionValue)
		{
			return new \ArrayIterator($collection->getValue());
		}
		elseif ($collection instanceof ResolvedCollectionResource)
		{
			if ($collection->getOrigin() instanceof Origin_Unavailable)
			{
				return new \ArrayIterator($this->database->getPosts());
			}
		}
		throw new NotImplementedException;
	}

	/**
	 * Returns a specification of a collection property.
	 * @param string $propertyName
	 * @return Property    A Property object, if the property exists; otherwise, NULL.
	 */
	public function getProperty($propertyName)
	{
		return $this->properties[$propertyName];
	}

	/**
	 * Returns all objects matching the scope.
	 * @param ResolvedCollection	$collection
	 * @param Scope\QueryScope  	$scope
	 * @param SearchContext      	$context
	 * @return \Iterator	An iterator over all objects matching the scope.
	 *                   	The key of the iterator should indicate the key of the object in the collection.
	 */
	public function find(ResolvedCollection $collection, Scope\QueryScope $scope, SearchContext $context)
	{
		// TODO
	}

}