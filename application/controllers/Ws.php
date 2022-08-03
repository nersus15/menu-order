<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ws extends CI_Controller{
    function baca_notif($nid){
        if($nid == 'all')
            $nid = $_POST['ids'];
        $this->notification->baca($nid);
	}
    function get_notif(){
        response($this->notification->getAll());
    }
}