<?php
namespace Light\ObjectAccess\Resource;

/**
 * Provides information about the origin of a resource.
 *
 * This class provides information about how the resource was obtained.
 */
abstract class Origin
{
	/**
	 * No information about the origin of the resource is available.
	 * @return Origin_Unavailable
	 */
	public static function unavailable()
	{
		return new Origin_Unavailable();
	}

	/**
	 * The resource was obtained from an element in a collection.
	 * @param ResolvedCollection $collection
	 * @param mixed              $key
	 * @return Origin_ElementInCollection
	 */
	public static function elementInCollection(ResolvedCollection $collection, $key)
	{
		return new Origin_ElementInCollection($collection, $key);
	}

	/**
	 * The resource was obtained by accessing a property of an object.
	 * @param ResolvedObject $object
	 * @param string         $propertyName
	 * @return Origin_PropertyOfObject
	 */
	public static function propertyOfObject(ResolvedObject $object, $propertyName)
	{
		return new Origin_PropertyOfObject($object, $propertyName);
	}
}

final class Origin_Unavailable extends Origin
{
}

final class Origin_ElementInCollection extends Origin
{
	/** @var ResolvedCollection */
	private $collection;
	private $key;

	protected function __construct(ResolvedCollection $collection, $key)
	{
		$this->collection = $collection;
		$this->key = $key;
	}

	/**
	 * @return ResolvedCollection
	 */
	public function getCollection()
	{
		return $this->collection;
	}

	/**
	 * @return mixed
	 */
	public function getKey()
	{
		return $this->key;
	}
}

final class Origin_PropertyOfObject extends Origin
{
	/** @var ResolvedObject */
	private $object;
	/** @var string */
	private $propertyName;

	protected function __construct(ResolvedObject $object, $propertyName)
	{
		$this->object = $object;
		$this->propertyName = $propertyName;
	}

	/**
	 * @return ResolvedObject
	 */
	public function getObject()
	{
		return $this->object;
	}

	/**
	 * @return string
	 */
	public function getPropertyName()
	{
		return $this->propertyName;
	}

}