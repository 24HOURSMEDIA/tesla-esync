<?php
/**
 * Created by PhpStorm.
 * User: eapbachman
 * Date: 12/07/14
 * Time: 21:07
 */

namespace Tesla\Esync\Message;


interface MessageFactoryInterface {

    /**
     * Set the eventDispatcher
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher($eventDispatcher);

    /**
     * @param $id
     * @return CudMessage
     */
    function createUpdateMessage($id, $class = 'undefined');
    /**
     * @param $id
     * @return CudMessage
     */
    function createDeleteMessage($id, $class = 'undefined');
    /**
     * @param $id
     * @return CudMessage
     */
    function createCreateMessage($id, $class = 'undefined');
    /**
     * @param $id
     * @return CudMessage
     */
    function create($type, $id, $class = 'undefined');
} 