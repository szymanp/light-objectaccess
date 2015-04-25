<?php
namespace Light\ObjectAccess\Type\Complex;

use Light\ObjectAccess\Resource\Addressing\ResourceAddress;

/**
 * An interface for complex types that can provide a canonical address for their objects.
 *
 * One of the uses of a canonical address is for assigning addresses to newly created objects.
 * Normally, a newly created object will have an empty address. However, if the type implements this interface,
 * then it will instead be assigned a canonical address.
 */
interface CanonicalAddress
{
	/**
	 * Returns a canonical address for the specified object.
	 * @param mixed	$object
	 * @return ResourceAddress
	 */
	public function getCanonicalAddress($object);
}