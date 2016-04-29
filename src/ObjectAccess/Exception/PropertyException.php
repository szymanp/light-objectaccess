<?php
namespace Light\ObjectAccess\Exception;

use Szyman\Exception\InvalidArgumentTypeException;
use Light\ObjectAccess\Type\Type;
use Light\ObjectAccess\Type\TypeHelper;
use Light\ObjectAccess\Type\Complex\Property as ComplexProperty;
use Light\ObjectAccess\Type\Collection\Property as CollectionProperty;

/**
 * An exception thrown if the property doesn't support the requested action.
 *
 * This exception indicates that the requested operation is not supported for the property.
 * It should not be used if the error is due to a misconfiguration of the property or type.
 *
 * For example:
 * <code>
 * throw new PropertyCapabilityException($type, $property, 'is not readable');
 * </code>
 */
class PropertyException extends TypeException
{
	/**
	 * @param Type|TypeHelper                           $type           The relevant type.
	 * @param ComplexProperty|CollectionProperty|string $property       The relevant property.
	 * @param string                                    $explanation    Explanation of the required capability.
	 */
	public function __construct($type, $property, $explanation)
	{
		if ($type instanceof TypeHelper)
		{
			$typename = $type->getName();
		}
		elseif ($type instanceof Type)
		{
			$typename = get_class($type);
		}
		else
		{
			throw new InvalidArgumentTypeException('$type', $type, 'Type|TypeHelper');
		}
        
        if ($property instanceof ComplexProperty || $property instanceof CollectionProperty)
        {
            $propertyname = $property->getName();
        }
        elseif (is_string($property) && !empty($property))
        {
            $propertyname = $property;
        }
        else
        {
            throw new InvalidArgumentTypeException('$property', $property, 'Property|string');
        }

		$message = 'Property ' . $typename . '::' . $propertyname . ' ' . trim($explanation);
		parent::__construct($message);
	}
}
