<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/* @var $events array Array of SN_Model_Event */

foreach($events as $event) { /* @var $event SN_Model_Event */
    include('inline_kalender_event.php');
}

?>