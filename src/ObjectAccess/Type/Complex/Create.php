<?php 
namespace Light\ObjectAccess\Type\Complex;

use Light\ObjectAccess\Transaction\Transaction;

/**
 * An interface for ComplexTypes that support creating new instances of objects.
 *
 */
interface Create
{
	/**
	 * Creates a new instance of an object of this complex-type.
	 * @param CreationContext	$context
	 * @return object
	 */
	public function createObject(CreationContext $context, Transaction $transaction);
}