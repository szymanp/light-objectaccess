<?php
namespace Light\ObjectAccess\Type\Util;

use Szyman\Exception\Exception;
use Szyman\Exception\InvalidArgumentTypeException;

/**
 * A class for setting and getting properties of PHP objects.
 *
 * This class will try to use getters and setters, if they exists, and then fall back on public fields.
 * The class does not support objects with magic __set() and __get() methods.
 */
class PhpObjectMutator
{
    /**
     * @var mixed
     */
    protected $object;

    /**
     * @param mixed $object A PHP object.
     */
    public function __construct($object)
    {
		if (!is_object($object))
		{
			throw new InvalidArgumentTypeException('$object', $object, 'object');
		}
        $this->object = $object;
    }

    /**
     * Reads the value of the named property.
     *
     * @param string $localName Name of the property.
     * @throws Exception If the property cannot be read.
     * @return mixed A value read from the property.
     */
    public function & getPropertyValue($localName)
    {
        $target = $this->object;

        if (property_exists($target, $localName))
        {
            $refl = new \ReflectionProperty(get_class($target), $localName);
            if ($refl->isPublic())
            {
                return $target->$localName;
            }
        }

        if (method_exists($target, $m = "get" . $localName))
        {
            $v = $target->$m();
            return $v;
        }

        if (method_exists($target, $m = "is" . $localName))
        {
            $v = $target->$m();
            return $v;
        }

        throw new Exception("Property %1::%2 cannot be read. No getter or public field was found.", get_class($target), $localName);
    }

	/**
	 * Sets the value of the named property.
	 * @param $localName	Name of the property.
	 * @param $value		The value to be assigned.
	 * @throws Exception	If the property cannot be written to.
	 */
    public function setPropertyValue($localName, $value)
    {
        $target = $this->object;

        if (property_exists($target, $localName))
        {
            $refl = new \ReflectionProperty(get_class($target), $localName);
            if ($refl->isPublic())
            {
                $target->$localName = $value;
                return;
            }
        }

        if (method_exists($target, $m = "set" . $localName))
        {
            $target->$m($value);
            return;
        }

        throw new Exception("Property %1::%2 is not available for writing. No setter or public field was found.", get_class($target), $localName);
    }
}
