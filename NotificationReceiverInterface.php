<?php
/**
 * Created by PhpStorm.
 * User: eapbachman
 * Date: 12/07/14
 * Time: 22:27
 */

namespace Tesla\Esync;

use Tesla\Esync\Message\CudMessage;

interface NotificationReceiverInterface {

    /**
     * @return CudMessage|null
     */
    function listen();
} 