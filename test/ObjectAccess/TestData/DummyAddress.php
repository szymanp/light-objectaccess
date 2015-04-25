<?php
namespace Light\ObjectAccess\TestData;

use Light\ObjectAccess\Query\Scope;
use Light\ObjectAccess\Resource\Addressing\ResourceAddress;

class DummyAddress implements ResourceAddress
{
	/** @var string */
	private $address;

	public function __construct($address)
	{
		$this->address = $address;
	}

	/** @inheritdoc */
	public function appendScope(Scope $scope)
	{
		return;
	}

	/** @inheritdoc */
	public function appendElement($pathElement)
	{
		return;
	}

	/** @inheritdoc */
	public function hasStringForm()
	{
		return true;
	}

	/**
	 * Returns the string representation of this address.
	 * @return string    An address string, if it is available; otherwise, NULL.
	 */
	public function getAsString()
	{
		return $this->address;
	}
}