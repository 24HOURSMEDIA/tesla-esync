<?php
/**
 * Created by PhpStorm.
 * User: eapbachman
 * Date: 12/07/14
 * Time: 20:57
 */

namespace Tesla\Esync\Message;


class UpdateMessage extends CudMessage {

    function __construct($id,$class = 'undefined') {
        $this->type = 'update';
        $this->setId($id)->setClass($class);
    }

} 