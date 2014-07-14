<?php
/**
 * Created by PhpStorm.
 * User: eapbachman
 * Date: 12/07/14
 * Time: 21:26
 */

namespace Tesla\Esync\Message;


interface MessageSerializerInterface {
    function serialize(CudMessage $message);
} 