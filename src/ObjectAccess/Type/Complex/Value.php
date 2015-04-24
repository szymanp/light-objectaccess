<?php
namespace Light\ObjectAccess\Type\Complex;

/**
 * A wrapper class for property values.
 */
abstract class Value
{
	/**
	 * A concrete value.
	 * @param mixed	$value
	 * @return Value_Concrete
	 */
	public static function of($value)
	{
		return new Value_Concrete($value);
	}

	/**
	 * An unavailable value.
	 * @param string $typeName
	 * @return Value_Unavailable
	 */
	public static function unavailable($typeName = null)
	{
		return new Value_Unavailable($typeName);
	}

	/**
	 * A null value.
	 * @return Value_NotExists
	 */
	public static function notExists()
	{
		return new Value_NotExists();
	}
}

final class Value_Concrete extends Value
{
	private $value;

	protected function __construct($value)
	{
		$this->value = $value;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}
}

final class Value_Unavailable extends Value
{
	/** @var string */
	private $typeName;

	protected function __construct($typeName = null)
	{
		$this->typeName = $typeName;
	}

	/**
	 * @return string
	 */
	public function getTypeName()
	{
		return $this->typeName;
	}
}

final class Value_NotExists extends Value
{
	protected function __construct()
	{
	}
}