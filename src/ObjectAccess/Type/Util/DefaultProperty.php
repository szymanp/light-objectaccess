<?php
namespace Light\ObjectAccess\Type\Util;

use Light\Data\Helper;
use Light\ObjectAccess\Exception\ResourceException;
use Light\ObjectAccess\Resource\ResolvedObject;
use Light\ObjectAccess\Transaction\Transaction;
use Light\ObjectAccess\Type\Complex\Value;

/**
 * A default implementation of a property.
 *
 * This implementation supports reading and writing property values for objects that expose
 * setters and getters following the pattern: get<propertyName> ()or is<propertyName>(), and set<propertyName>($value),
 * or where the properties are exposed as public fields.
 * Alternatively, a callback can be specified for a customer setter and/or getter logic.
 *
 */
class DefaultProperty extends AbstractProperty
{
	/** @var bool */
	private $readable;
	/** @var bool */
	private $writable;
	/** @var \Closure */
	private $getter;
	/** @var \Closure */
	private $setter;

	/**
	 * Constructs a new DefaultProperty.
	 * @param string    $name
	 * @param string	$typeName
	 */
	public function __construct($name, $typeName = null)
	{
		parent::__construct($name, $typeName);
		$this->readable = true;
		$this->writable = true;
	}

	/**
	 * Returns true if the property can be read.
	 * @return bool
	 */
	public function isReadable()
	{
		return $this->readable;
	}

	/**
	 * Returns true if the property can be written to.
	 * @return bool
	 */
	public function isWritable()
	{
		return $this->writable;
	}

	/**
	 * Reads a value from this property on the specified resource.
	 *
	 * @param ResolvedObject $object
	 * @throws \Exception        If the property cannot be read.
	 * @return Value	A Value object holding the property value, or an indication that a concrete value is not available.
	 */
	public function readProperty(ResolvedObject $object)
	{
		try
		{
			if ($this->getter)
			{
				$rawValue = call_user_func($this->getter, $this, $object->getValue());
			}
			else
			{
				$wrapped = Helper::wrap($object->getValue());
				$rawValue = $wrapped->getValue($this->getName());
			}
			return is_null($rawValue) ? Value::notExists() : Value::of($rawValue);
		}
		catch (\Exception $e)
		{
			throw new ResourceException("Property %1::%2 cannot be read: %3", get_class($object->getValue()), $this->getName(), $e->getMessage(), $e);
		}
	}

	/**
	 * Sets the value for the property on the specified resource.
	 *
	 * @param ResolvedObject $object
	 * @param mixed          $value
	 * @param Transaction    $transaction
	 * @throws \Exception        If the property cannot be written to.
	 */
	public function writeProperty(ResolvedObject $object, $value, Transaction $transaction)
	{
		try
		{
			if ($this->setter)
			{
				call_user_func($this->setter, $this, $object->getValue(), $value);
			}
			else
			{
				$wrapped = Helper::wrap($object->getValue());
				$wrapped->setValue($this->getName(), $value);
			}
			$transaction->markAsChanged($object);
		}
		catch (\Exception $e)
		{
			throw new ResourceException("Field %1::%2 cannot be written: %3", get_class($object->getValue()), $this->getName(), $e->getMessage(), $e);
		}
	}

	/**
	 * @param $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @param string $typeName
	 * @return $this
	 */
	public function setTypeName($typeName)
	{
		$this->typeName = $typeName;
		return $this;
	}

	/**
	 * @param boolean $readable
	 * @return $this
	 */
	public function setReadable($readable)
	{
		$this->readable = $readable;
		return $this;
	}

	/**
	 * @param boolean $writable
	 * @return $this
	 */
	public function setWritable($writable)
	{
		$this->writable = $writable;
		return $this;
	}

	/**
	 * @param \Closure $getter	A function of the form: mixed getter(Property $property, object $object)
	 * @return $this
	 */
	public function setGetter(\Closure $getter)
	{
		$this->getter = $getter;
		return $this;
	}

	/**
	 * @param \Closure $setter	A function of the form: void setter(Property $property, object $object, $value)
	 * @return $this
	 */
	public function setSetter(\Closure $setter)
	{
		$this->setter = $setter;
		return $this;
	}
}