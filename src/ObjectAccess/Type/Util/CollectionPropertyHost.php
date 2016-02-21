<?php
namespace Light\ObjectAccess\Type\Util;

use Szyman\Exception\Exception;
use Light\ObjectAccess\Type\Collection\Property;

class CollectionPropertyHost implements \IteratorAggregate, \ArrayAccess
{
	private $properties = array();

	/**
	 * Adds a new property to this object.
	 * @param Property $property
	 * @return $this
	 */
	public function append(Property $property)
	{
		$this->properties[$property->getName()] = $property;
		return $this;
	}

	/**
	 * Returns an iterator over all properties in this object.
	 * @return \Iterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->properties);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Whether a offset exists
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset <p>
	 *                      An offset to check for.
	 *                      </p>
	 * @return boolean true on success or false on failure.
	 *                      </p>
	 *                      <p>
	 *                      The return value will be casted to boolean if non-boolean was returned.
	 */
	public function offsetExists($offset)
	{
		return isset($this->properties[$offset]);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Offset to retrieve
	 * @link http://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset <p>
	 *                      The offset to retrieve.
	 *                      </p>
	 * @return mixed Can return all value types.
	 */
	public function offsetGet($offset)
	{
		if (isset($this->properties[$offset]))
		{
			return $this->properties[$offset];
		}
		else
		{
			return null;
		}
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Offset to set
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset <p>
	 *                      The offset to assign the value to.
	 *                      </p>
	 * @param mixed $value  <p>
	 *                      The value to set.
	 *                      </p>
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		if ($value instanceof Property)
		{
			$this->append($value);
		}
		else
		{
			throw new Exception("Only Property objects can be added to this class");
		}
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Offset to unset
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset <p>
	 *                      The offset to unset.
	 *                      </p>
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		unset($this->properties[$offset]);
	}
}