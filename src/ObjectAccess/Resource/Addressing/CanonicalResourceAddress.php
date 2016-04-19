<?php
namespace Light\ObjectAccess\Resource\Addressing;

use Light\ObjectAccess\Query\Scope;

/**
 * A wrapper class that marks an address as a canonical address.
 *
 * When a resource's address is marked as a canonical address, then the object access
 * framework will not attempt to replace it with a new canonical address obtained
 * from the associated complex type.
 */
final class CanonicalResourceAddress implements ResourceAddress
{
	/** @var ResourceAddress */
	private $address;
	
	/**
	 * Wraps an address in a <kbd>CanonicalResourceAddress</kbd>.
	 * @param ResourceAddress $address
	 * @return CanonicalResourceAddress
	 */
	public static function create(ResourceAddress $address)
	{
		if ($address instanceof CanonicalResourceAddress)
		{
			return $address;
		}
		else
		{
			return new self($address);
		}
	}
	
	private function __construct(ResourceAddress $address)
	{
		$this->address = $address;
	}

	/** @inheritdoc */
	public function appendScope(Scope $scope)
	{
		return $this->address->appendScope($scope);
	}

	/** @inheritdoc */
	public function appendElement($pathElement)
	{
		return $this->address->appendElement($pathElement);
	}

	/** @inheritdoc */
	public function hasStringForm()
	{
		return $this->address->hasStringForm();
	}

	/** @inheritdoc */
	public function getAsString()
	{
		return $this->address->getAsString();
	}
	
	/**
	 * Returns the wrapped address.
	 * @return ResourceAddress
	 */
	public function getInnerAddress()
	{
		return $this->address;
	}
}
