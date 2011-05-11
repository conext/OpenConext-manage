<?php

class Model_Abstract
{
    /**
     * 
     * 
     * @var Model_Mapper_Abstract
     */
    protected $_mapper;

    /**
     * Set the mapper
     *
     * @param <type> Model_Mapper_Abstract
     * @return Model_Abstract
     */
    public function setMapper(Model_Mapper_Abstract $mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    /**
     * Get the data mapper
     * 
     * @return Model_Mapper_Abstract
     */
    public function getMapper()
    {
        return $this->_mapper;
    }

    /**
     * Load data
     *
     * @param Integer $id
     */
    public function load($id)
    {
        if (is_int($id) || (is_string($id) && ctype_digit($id))) {
            $this->getMapper()->find($id, $this);
        } else {
            throw new InvalidArgumentException('id must be numeric');
        }
    }
}
