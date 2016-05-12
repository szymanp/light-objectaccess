<?php
namespace Light\ObjectAccess\Type\Complex;

use Light\ObjectAccess\Resource\ResolvedObject;
use Light\ObjectAccess\Transaction\Transaction;

interface Property
{
	/**
	 * Returns the name of this property.
	 * @return string
	 */
	public function getName();

	/**
	 * Returns the name of this property's type.
	 * @return string
	 */
	public function getTypeName();

	/**
	 * Returns true if the property can be read.
	 * @return bool
	 */
	public function isReadable();

	/**
	 * Returns true if the property can be written to when the object is being created.
	 * @return bool
	 */
	public function isWritableOnCreate();

	/**
	 * Returns true if the property can be written to when the object is modified.
	 * @return bool
	 */
	public function isWritableOnUpdate();

	/**
	 * Reads a value from this property on the specified resource.
	 *
	 * @param ResolvedObject    $object
	 * @throws \Exception		If the property cannot be read.
	 * @return Value	A Value object holding the property value, or an indication that a concrete value is not available.
	 */
	public function readProperty(ResolvedObject $object);

	/**
	 * Sets the value for the property on the specified resource.
	 *
	 * @param ResolvedObject    $object
	 * @param mixed         	$value
	 * @param Transaction  	 	$transaction
	 * @throws \Exception		If the property cannot be written to.
	 */
	public function writeProperty(ResolvedObject $object, $value, Transaction $transaction);
}