<?php
namespace Light\ObjectAccess\Resource\Addressing;

use Light\ObjectAccess\Resource\ResolvedResource;

/**
 * Provides a path from a source resource to a destination resource.
 */
interface RelativeAddress
{
	/**
	 * Returns the source resource.
	 * @return ResolvedResource
	 */
	public function getSourceResource();

	/**
	 * Returns a list of path elements to traverse for reaching the target resource.
	 * @return mixed[]
	 */
	public function getPathElements();
}