<?php
/**
 * Created by PhpStorm.
 * User: eapbachman
 * Date: 12/07/14
 * Time: 21:05
 */

namespace Tesla\Esync\Message;


use Tesla\Esync\Event\MessageEvent;
use Tesla\Esync\Exception\CudException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MessageFactory implements MessageFactoryInterface
{

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * Set the eventDispatcher
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher($eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }

    /**
     * @param $id
     * @return UpdateMessage
     */
    function createUpdateMessage($id, $class = 'undefined')
    {
        return $this->create('update', $id, $class);
    }

    /**
     * @param $id
     * @return DeleteMessage
     */
    function createDeleteMessage($id, $class = 'undefined')
    {
        return $this->create('delete', $id, $class);
    }

    /**
     * @param $id
     * @return CreateMessage
     */
    function createCreateMessage($id, $class = 'undefined')
    {
        return $this->create('create', $id, $class);
    }

    /**
     * @param $id
     * @return CudMessage
     */
    function create($type, $id, $class = 'undefined')
    {
        switch ($type) {
            case 'create':
                $message = new CreateMessage($id, $class);
                break;
            case 'delete':
                $message = new DeleteMessage($id, $class);
                break;
            case 'update':
                $message = new UpdateMessage($id, $class);
                break;
            default:
                throw new CudException('Unknown type ' . $type);
        }
        if ($this->eventDispatcher) {
            $messageEvent = new MessageEvent($message);
            $this->eventDispatcher->dispatch(MessageEvent::ON_CREATE, $messageEvent);
        }
        return $message;

    }
} 