<?php
/**
 * Created by PhpStorm.
 * User: eapbachman
 * Date: 12/07/14
 * Time: 22:29
 */

namespace Tesla\Esync\Event;


use Symfony\Component\EventDispatcher\Event;
use Tesla\Esync\Message\CudMessage;

class MessageEvent extends Event {

    const ON_CREATE = 'ON_CREATE';
    const ON_RECEIVE = 'ON_RECEIVE';

    protected $message;

    function __construct(CudMessage $message) {
        $this->message = $message;

    }

    /**
     * Get the Message
     * @return CudMessage
     */
    public function getMessage()
    {
        return $this->message;
    }


} 