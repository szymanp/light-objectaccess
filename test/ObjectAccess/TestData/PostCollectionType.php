<?php
namespace Light\ObjectAccess\TestData;

use Szyman\Exception\Exception;
use Szyman\Exception\NotImplementedException;
use Light\ObjectAccess\Query\Query;
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

include_once("test/ObjectAccess/TestData/QueryFilterIterator.php");

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
			// TODO What about adding the collection to the transaction?
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
	 * @param ResolvedCollectionValue $collection
	 * @return \Iterator	An iterator over the elements in the collection.
	 *                   	The key of the iterator should indicate the key of the object in the collection.
	 */
	public function getIterator(ResolvedCollectionValue $collection)
	{
		return new \ArrayIterator($collection->getValue());
	}

	/**
	 * Returns all the elements of the collection.
	 *
	 * This method will be called if all the elements of a collection need to be retrieved,
	 * for example when a search using {@link EmptyScope} is invoked.
	 *
	 * @param ResolvedCollectionResource $collection
	 * @return mixed    All the elements of the collection.
	 */
	public function read(ResolvedCollectionResource $collection)
	{
		$origin = $collection->getOrigin();
		if ($origin instanceof Origin_Unavailable)
		{
			return $this->database->getPosts();
		}
		elseif ($origin instanceof Origin_PropertyOfObject)
		{
			$object = $origin->getObject()->getValue();
			if ($object instanceof Author && $origin->getPropertyName() == "posts")
			{
				return $this->database->getPostsForAuthor($object);
			}
			else
			{
				throw new Exception("PostCollectionType only supports Author objects, not %1::%2",
					get_class($object),
					$origin->getPropertyName());
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
	 * Returns all elements of the collection matching the query scope.
	 * @param ResolvedCollectionResource	$collection
	 * @param Scope\QueryScope				$scope
	 * @param SearchContext					$context
	 * @return mixed	Elements of the collection matching the scope.
	 */
	public function find(ResolvedCollectionResource $collection, Scope\QueryScope $scope, SearchContext $context)
	{
		$offset = $scope->getOffset() ?: 0;
		$count = $scope->getCount() ?: -1;

		$innerIterator = new \ArrayIterator($this->read($collection));
		$iterator = new \LimitIterator(new QueryFilterIterator($innerIterator, $scope->getQuery()), $offset, $count);
		return iterator_to_array($iterator);
	}
}