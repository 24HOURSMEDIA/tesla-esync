<?php
/**
 * Created by PhpStorm.
 * User: eapbachman
 * Date: 12/07/14
 * Time: 21:10
 */

namespace Tesla\Esync\Message;


class JsonMessageSerializer  implements MessageSerializerInterface {


    function serialize(CudMessage $message) {

        return json_encode(
          array(
              'type' => $message->getType(),
              'id' => $message->getId(),
              'data' => $message->getData(),
              'class' => $message->getClass()
          )
        );


    }



} 