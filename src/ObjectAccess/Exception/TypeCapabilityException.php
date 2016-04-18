<?php
namespace Light\ObjectAccess\Exception;

use Szyman\Exception\InvalidArgumentException;
use Light\ObjectAccess\Type\Type;
use Light\ObjectAccess\Type\TypeHelper;

/**
 * An exception thrown if a type doesn't implement the required capability.
 *
 * For example:
 * <code>
 * throw new TypeCapabilityException($this, Create::class, 'Type does not support creation of objects');
 * </code>
 */
class TypeCapabilityException extends TypeException
{
	/**
	 * @param Type|TypeHelper	$type				The relevant type.
	 * @param string			$requiredInterface	The missing interface or capability.
	 * @param string			$explanation		Explanation of the required capability.
	 */
	public function __construct($type, $requiredInterface, $explanation = "")
	{
		if ($type instanceof TypeHelper)
		{
			$name = $type->getName();
		}
		elseif ($type instanceof Type)
		{
			$name = get_class($type);
		}
		else
		{
			throw InvalidArgumentException::newInvalidType('$type', $type, 'Type|TypeHelper');
		}
		
		$message = 'Type "' . $name . '" does not have capability "' . $requiredInterface . '"';
		if (!empty($explanation))
		{
			$message .= '. ' . $explanation;
		}
		
		parent::__construct($message);
	}
}