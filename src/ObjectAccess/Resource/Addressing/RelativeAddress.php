<?php
namespace Light\ObjectAccess\Resource\Addressing;

use Light\ObjectAccess\Resource\ResolvedValue;

/**
 * Provides a path from a source resource to a destination resource.
 */
interface RelativeAddress
{
	/**
	 * Returns the source resource.
	 * @return ResolvedValue
	 */
	public function getSourceResource();

	/**
	 * Returns a list of path elements to traverse for reaching the target resource.
	 * @return mixed[]
	 */
	public function getPathElements();
}