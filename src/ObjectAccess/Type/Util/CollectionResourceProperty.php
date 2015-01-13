<?php
namespace Light\ObjectAccess\Type\Util;

use Light\ObjectAccess\Resource\ResolvedObject;
use Light\ObjectAccess\Transaction\Transaction;
use Light\ObjectAccess\Type\Complex\Value;

class CollectionResourceProperty extends AbstractProperty
{
	/**
	 * Returns true if the property can be read.
	 * @return bool
	 */
	public function isReadable()
	{
		return true;
	}

	/**
	 * Returns true if the property can be written to.
	 * @return bool
	 */
	public function isWritable()
	{
		return false;
	}

	/**
	 * Reads a value from this property on the specified resource.
	 *
	 * @param ResolvedObject $object
	 * @throws \Exception        If the property cannot be read.
	 * @return Value    A Value object holding the property value, or an indication that a concrete value is not available.
	 */
	public function readProperty(ResolvedObject $object)
	{
		return Value::unavailable($this->getTypeName());
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
		// TODO: Implement writeProperty() method.
	}

}