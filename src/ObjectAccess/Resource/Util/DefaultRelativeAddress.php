<?php
namespace Light\ObjectAccess\Resource\Util;

use Light\ObjectAccess\Resource\Addressing\RelativeAddress;
use Light\ObjectAccess\Resource\ResolvedResource;

/**
 * A RelativeAddress implementation that is constructed in a programmatic way.
 */
class DefaultRelativeAddress implements RelativeAddress
{
	/** @var ResolvedResource */
	private $sourceResource;

	/** @var array */
	private $elements;

	/**
	 * Constructs a new DefaultRelativeAddress object.
	 * @param ResolvedResource $sourceResource
	 */
	public function __construct(ResolvedResource $sourceResource)
	{
		$this->sourceResource = $sourceResource;
	}

	/**
	 * @param Scope $scope
	 * @return $this
	 */
	public function appendScope(Scope $scope)
	{
		$this->elements[] = $scope;
		return $this;
	}

	/**
	 * @param $index
	 * @return $this
	 */
	public function appendIndex($index)
	{
		$this->elements[] = (int)$index;
		return $this;
	}

	/**
	 * @param string $pathElement
	 * @return $this
	 */
	public function appendElement($pathElement)
	{
		$this->elements[] = $pathElement;
		return $this;
	}

	/**
	 * Returns the source resource.
	 * @return ResolvedResource
	 */
	public function getSourceResource()
	{
		return $this->sourceResource;
	}

	/**
	 * Returns a list of path elements to traverse for reaching the target resource.
	 * @return mixed[]
	 */
	public function getPathElements()
	{
		return $this->elements;
	}
}