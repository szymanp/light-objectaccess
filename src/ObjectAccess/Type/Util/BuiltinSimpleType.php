<?php 
namespace Light\ObjectAccess\Type\Util;

use Light\ObjectAccess\Type\SimpleType;

final class BuiltinSimpleType implements SimpleType
{
	private static $builtinTypes = array("bool", "boolean", "int", "integer", "float", "real", "string");

	private $phpType;

	/**
	 * Returns true if the specified type is one of the built-in PHP types.
	 * @param string $name
	 * @return boolean
	 */
	public static function isBuiltinType($name)
	{
		$name = strtolower(trim($name));
		return in_array($name, self::$builtinTypes);
	}

	public function __construct($type)
	{
		$this->phpType = $type;
	}

	public function getPhpType()
	{
		return $this->phpType;
	}
}