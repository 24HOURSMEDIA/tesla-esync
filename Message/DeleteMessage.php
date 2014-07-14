<?php
/**
 * Created by PhpStorm.
 * User: eapbachman
 * Date: 12/07/14
 * Time: 20:58
 */

namespace Tesla\Esync\Message;


class DeleteMessage extends CudMessage {


    function __construct($id,$class = 'undefined') {
        $this->type = 'delete';
        $this->setId($id)->setClass($class);
    }

} 