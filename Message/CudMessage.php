<?php
/**
 * Created by PhpStorm.
 * User: eapbachman
 * Date: 12/07/14
 * Time: 20:55
 */

namespace Tesla\Esync\Message;


abstract class CudMessage {

    private $id;
    private $class;

    protected $type;

    protected $data = array();


    /**
     * Set the id
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the Id
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the class
     * @param mixed $class
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get the Class
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set the type
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the Type
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the data
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the Data
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }



} 