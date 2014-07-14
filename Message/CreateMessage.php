<?php
/**
 * Created by PhpStorm.
 * User: eapbachman
 * Date: 12/07/14
 * Time: 20:57
 */

namespace Tesla\Esync\Message;


class CreateMessage extends CudMessage {
    function __construct($id,$class = 'undefined') {
        $this->type = 'create';
        $this->setId($id)->setClass($class);
    }
} 