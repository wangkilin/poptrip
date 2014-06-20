<?php
abstract class ClassAbstract
{
    public $debug = false;
    protected $errorDesc = '';

    public function setDebug ($debug)
    {
        $this->debug = (bool) $debug;

        return $this;
    }

    public function getError ()
    {
        return $this->errorDesc;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
        if (!method_exists($this, $setter)) {
            throw new Exception(
                'The option "' . $key . '" does not '
                . 'have a matching ' . $setter . ' setter method '
                . 'which must be defined'
            );
        }

        $this->{$setter}($value);
    }

    /**
     * @param string $key
     * @throws Exception\BadMethodCallException
     * @return mixed
     */
    public function __get($key)
    {
        $getter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
        if (!method_exists($this, $getter)) {
            throw new Exception(
                'The option "' . $key . '" does not '
                . 'have a matching ' . $getter . ' getter method '
                . 'which must be defined'
            );
        }

        return $this->{$getter}();
    }

    public function set($key, $value)
    {
        $this->__set($key, $value);

        return $this;
    }

    public function get($key)
    {
        return $this->__get($key);
    }

}
