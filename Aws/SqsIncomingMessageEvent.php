<?php
/**
 * Created by PhpStorm.
 * User: eapbachman
 * Date: 12/07/14
 * Time: 23:54
 */

namespace Tesla\Esync\Aws;


use Tesla\Esync\Event\IncomingMessageEvent;
use Tesla\Esync\Message\CudMessage;

class SqsIncomingMessageEvent extends IncomingMessageEvent {

    private $envelope;

    function __construct(CudMessage $message, array $sqsMessageEnvelope) {
        parent::__construct($message);
        $this->envelope = $sqsMessageEnvelope;
    }

    function getEnvelope() {
        return $this->envelope;
    }

} 