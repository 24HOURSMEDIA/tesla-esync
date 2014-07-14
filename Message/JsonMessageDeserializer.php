<?php
/**
 * Created by PhpStorm.
 * User: eapbachman
 * Date: 12/07/14
 * Time: 21:13
 */

namespace Tesla\Esync\Message;


use Tesla\Esync\Exception\DeserializationException;

class JsonMessageDeserializer implements MessageDeserializerInterface
{

    /**
     * @var MessageFactoryInterface
     */
    private $factory;

    function __construct(MessageFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param $str
     * @return CudMessage
     * @throws \Tesla\Esync\Exception\DeserializationException
     */
    function deserialize($str)
    {

        $data = json_decode($str, true);

        if (
            !$data
            || !isset($data['id'])
            || !isset($data['type'])
        ) {
            throw new DeserializationException('Could not decode message');
        }

        $message = $this->factory->create($data['type'], $data['id'], $data['class']);

        return $message;

    }

} 