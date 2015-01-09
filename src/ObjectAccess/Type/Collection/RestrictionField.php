<?php
namespace Light\ObjectAccess\Type\Collection;

interface FilterableProperty
{
	public function getName();

	public function getTypeName();

	public function isCriterion();
}