<?php

namespace WsdlToPhp\PackageBase;

abstract class AbstractStructBase implements StructInterface, \JsonSerializable
{
    /**
     * Returns the properties of this object
     * @return mixed[]
     */
    public function jsonSerialize()
    {
        return \get_object_vars($this);
    }
    /**
     * Generic method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @uses AbstractStructBase::_set()
     * @param array $array the exported values
     * @return self
     */
    public static function __set_state(array $array)
    {
        $className = get_called_class();
        $object = new $className();
        foreach ($array as $name => $value) {
            $object->_set($name, $value);
        }
        return $object;
    }
    /**
     * Generic method setting value
     * @throws \InvalidArgumentException
     * @param string $name property name to set
     * @param mixed $value property value to use
     * @return self
     */
    public function _set($name, $value)
    {
        @trigger_error(sprintf('%s() will be renamed to setPropertyValue in WsdlToPhp/PackageBase 4.0.', __METHOD__), E_USER_DEPRECATED);

        $setMethod = 'set' . ucfirst($name);
        if (method_exists($this, $setMethod)) {
            $this->$setMethod($value);
        } else {
            throw new \InvalidArgumentException(sprintf('Setter does not exist for "%s" property', $name));
        }
        return $this;
    }
    /**
     * Generic method getting value
     * @throws \InvalidArgumentException
     * @param string $name property name to get
     * @return mixed
     */
    public function _get($name)
    {
        @trigger_error(sprintf('%s() will be renamed to getPropertyValue in WsdlToPhp/PackageBase 4.0.', __METHOD__), E_USER_DEPRECATED);

        $getMethod = 'get' . ucfirst($name);
        if (method_exists($this, $getMethod)) {
            return $this->$getMethod();
        }
        throw new \InvalidArgumentException(sprintf('Getter does not exist for "%s" property', $name));
    }
    /**
     * Default string representation of current object. Don't want to expose any sensible data
     * @return string
     */
    public function __toString()
    {
        return get_called_class();
    }
}
