<?php 
namespace Light\ObjectAccess\Query\Argument;

class Criterion implements QueryArgument
{
	const EQ	= "=";
	const GT	= ">";
	const LT	= "<";
	const LIKE	= "LIKE";

	private $value;
	private $operator;
	
	public function __construct($value, $operator = self::EQ)
	{
		$this->value = $value;
		$this->operator = $operator;
	}
	
	public function getOperator()
	{
		return $this->operator;
	}

	public function getValue()
	{
		return $this->value;
	}
}