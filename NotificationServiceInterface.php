<?php
/**
 * Created by PhpStorm.
 * User: eapbachman
 * Date: 12/07/14
 * Time: 21:35
 */

namespace Tesla\Esync;


interface NotificationServiceInterface {

    function notifyCreate($id, $data = array(), $class = 'undefined');
    function notifyUpdate($id, $data = array(), $class = 'undefined');
    function notifyDelete($id, $data = array(), $class = 'undefined');

} 