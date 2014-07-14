<?php
/**
 * Created by PhpStorm.
 * User: eapbachman
 * Date: 12/07/14
 * Time: 21:27
 */

namespace Tesla\Esync;


use Tesla\Esync\Message\CudMessage;
use Tesla\Esync\Message\MessageDeserializerInterface;
use Tesla\Esync\Message\MessageFactoryInterface;
use Tesla\Esync\Message\MessageSerializerInterface;

abstract class AbstractNotificationService implements NotificationServiceInterface
{

    /**
     * @var Message\MessageFactoryInterface
     */
    protected $messageFactory;

    /**
     * @var Message\MessageSerializerInterface
     */
    protected $messageSerializer;



    public function __construct(
        MessageFactoryInterface $messageFactory = null,
        MessageSerializerInterface $messageSerializer = null

    ) {
        $this->messageFactory = $messageFactory;
        $this->messageSerializer = $messageSerializer;
    }

    function getMessageFactory() {
        return $this->messageFactory;
    }

    function notifyCreate($id, $data = array(), $class = 'undefined')
    {
        $message = $this->getMessageFactory()->createCreateMessage($id, $class)->setData($data);

        return $this->sendNotification($message);
    }

    function notifyUpdate($id, $data = array(), $class = 'undefined')
    {
        $message = $this->getMessageFactory()->createUpdateMessage($id, $class)->setData($data);

        return $this->sendNotification($message);
    }

    function notifyDelete($id, $data = array(), $class = 'undefined')
    {
        $message =$this->getMessageFactory()->createDeleteMessage($id, $class)->setData($data);

        return $this->sendNotification($message);
    }

    abstract protected function sendNotification(CudMessage $message);


} 