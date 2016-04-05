<?php
namespace Light\ObjectAccess\Resource;

use Szyman\Exception\Exception;

/**
 * Provides detailed information about every step of the path resolution by RelativeAddressReader.
 */
class ResolutionTrace implements \IteratorAggregate, \Countable
{
	/** @var ResolvedResource */
	private $source;
	/** @var ResolutionTrace_Element[] */
	private $trace = array();
	/** @var bool */
	private $finalized = false;
	
	/**
	 * Constructs a new ResolutionTrace object.
	 * @param ResolvedResource $source	The starting object.
	 */
	public function __construct(ResolvedResource $source)
	{
		$this->source = $source;
	}

	/**
	 * Store information about resolving a new path element.
	 * @param mixed				$pathElement
	 * @param ResolvedResource	$resource
	 * @param bool				$isFinal
	 */	
	public function append($pathElement, ResolvedResource $resource, $isFinal = false)
	{
		if ($this->finalized)
		{
			throw new Exception("The trace has been finalized. No more elements can be appended");
		}

		$previous = count($this->trace) > 0 ? $this->trace[count($this->trace) - 1] : null;

		$this->trace[] = new ResolutionTrace_Element($pathElement, $resource, $previous);
		$this->finalized = $isFinal;
	}

	public function count()
	{
		return count($this->trace);
	}

	public function getIterator()
	{
		return new \ArrayIterator($this->trace);
	}

	/**
	 * Returns the first resolved element in the path.
	 * @return ResolutionTrace_Element
	 */
	public function first()
	{
		return (count($this->trace) > 0) ? $this->trace[0] : null;
	}

	/**
	 * Returns the last resolved element in the path.
	 * @return ResolutionTrace_Element
	 */
	public function last()
	{
		return (count($this->trace) > 0) ? $this->trace[count($this->trace) - 1] : null;
	}

	/**
	 * Returns true if the entire path was resolved.
	 * @return boolean
	 */
	public function isFinalized()
	{
		return $this->finalized;
	}
}

final class ResolutionTrace_Element
{
	private $pathElement;
	private $resource;
	private $previous;

	public function __construct($pathElement, ResolvedResource $resource, ResolutionTrace_Element $previous = null)
	{
		$this->pathElement = $pathElement;
		$this->resource    = $resource;
		$this->previous    = $previous;
	}
	
	/**
	 * Returns the path element used to resolve this resource.
	 * @return mixed
	 */
	public function getPathElement()
	{
		return $this->pathElement;
	}
	
	/**
	 * Returns the resource that was resolved at this path element.
	 * @return ResolvedResource
	 */
	public function getResource()
	{
		return $this->resource;
	}

	/**
	 * Returns the previous element.
	 * @return ResolutionTrace_Element
	 */
	public function previous()
	{
		return $this->previous;
	}
}
