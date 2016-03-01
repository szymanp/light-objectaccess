<?php
namespace Light\ObjectAccess\Resource;

use Light\ObjectAccess\Exception\AddressResolutionException;
use Light\ObjectAccess\Query\Scope;
use Light\ObjectAccess\Resource\Addressing\RelativeAddress;
use Light\ObjectAccess\Type\Util\EmptySearchContext;

class RelativeAddressReader
{
	/** @var RelativeAddress */
	private $relativeAddress;
	
	/** @var ResolutionTrace */
	private $resolutionTrace;

	public function __construct(RelativeAddress $relativeAddress)
	{
		$this->relativeAddress = $relativeAddress;
	}

	/**
	 * Reads a target resource from the relative address.
	 * @return ResolvedResource		A resource object, if the address was resolved to a resource;
	 *                          	otherwise, NULL.
	 * @throws AddressResolutionException
	 */
	public function read()
	{
		$pathElements = $this->relativeAddress->getPathElements();
		$count 		  = count($pathElements);
		$resource	  = $this->relativeAddress->getSourceResource();
		
		$this->resolutionTrace = new ResolutionTrace($resource);
		
		foreach($pathElements as $index => $element)
		{
			try
			{
				$resource = $this->readElement($resource, $element, $index + 1 == $count);

				if (is_null($resource))
				{
					return null;
				}
				else
				{
					$this->resolutionTrace->append($element, $resource, $index + 1 == $count);
				}
			}
			catch (\Exception $e)
			{
				$addr = $resource->getAddress()->hasStringForm() ? '"' . $resource->getAddress()->getAsString() . '"' : "(unavailable)";
				throw new AddressResolutionException(
					"%1 (while resolving path %3 at element %2)",
					empty($e->getMessage()) ? get_class($e) . " in " . $e->getFile() . ":" . $e->getLine() : $e->getMessage(),
					is_scalar($element) ? '"' . $element . '"' : $element,
					$addr,
					$e);
			}
		}

		return $resource;
	}
	
	/**
	 * Returns information about every step in the path resolution of the last read() call.
	 * @return ResolutionTrace
	 */
	public function getLastResolutionTrace()
	{
		return $this->resolutionTrace;
	}

	/**
	 * @param ResolvedResource $resource
	 * @param mixed            $element
	 * @param boolean          $isLastElement
	 * @return ResolvedResource
	 * @throws AddressResolutionException
	 * @throws \Light\Exception\NotImplementedException
	 * @throws \Light\ObjectAccess\Exception\ResourceException
	 * @throws \Light\ObjectAccess\Exception\TypeException
	 */
	private function readElement(ResolvedResource $resource, $element, $isLastElement)
	{
		if (is_string($element))
		{
			// The element is either a property name of an object or a key in an array.

			if ($resource instanceof ResolvedCollection)
			{
				return $resource->getTypeHelper()->getElementAtKey($resource, $element);
			}
			elseif ($resource instanceof ResolvedObject)
			{
				return $resource->getTypeHelper()->readProperty($resource, $element);
			}
			else
			{
				throw new AddressResolutionException("Cannot read \"%1\" from resource of type %2", $element, $resource->getTypeHelper()->getName());
			}
		}
		elseif ($element instanceof Scope)
		{
			// The current resource must be a collection.

			if ($resource instanceof ResolvedCollectionResource)
			{
				$scope = $element;
				if ($scope instanceof Scope\QueryScope)
				{
					$searchContext = EmptySearchContext::create();
					return $resource->getTypeHelper()->queryCollection($resource, $scope, $searchContext);
				}
				else
				{
					return $resource->getTypeHelper()->applyScope($resource, $scope);
				}
			}
			elseif ($resource instanceof ResolvedCollectionValue)
			{
				throw new AddressResolutionException("A scope cannot be applied to a collection of values");
			}
			else
			{
				throw new AddressResolutionException("Resource is not a collection");
			}
		}
		elseif (is_integer($element))
		{
			// The element is a key in a collection.

			if ($resource instanceof ResolvedCollection)
			{
				return $resource->getTypeHelper()->getElementAtKey($resource, $element);
			}
			else
			{
				throw new AddressResolutionException("Resource is not a collection");
			}
		}
		else
		{
			throw new \LogicException(get_class($element));
		}
	}
}
