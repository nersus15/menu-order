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
    function order($detail= 0, $token = ''){
        $this->load->model('Order_model');
        if(!is_login()) response('Anda belum login', 403);
        if($detail == 0)
            $tmp = $this->Order_model->getby(['tanggal' => waktu(null, MYSQL_DATE_FORMAT), 'pesanan.status' => 'OPEN'], false);
        else
            $tmp = $this->Order_model->getby(['token' => $token]);

        $pesanan = [];
        foreach($tmp as $v){
            if(!isset( $pesanan[$v['token']])){
                $pesanan[$v['token']] = array(
                    'atasnama' => $v['atasnama'],
                    'meja' => $v['meja'],
                );
            }
        }
        response(['data' => $detail == 0 ? $pesanan : $tmp]);
    }
    function order_list($tanggal = 'semua'){
        $this->load->model('Order_model');
        if(!is_login()) response('Anda belum login', 403);
        if($tanggal == 'semua')
            $tmp = $this->Order_model->getby([ 'pesanan.status' => 'CLOSE'], false);
        else{
			$tgl = explode('_', $tanggal);
            $tmp = $this->Order_model->getby([ 'pesanan.status' => 'CLOSE', 'DATE(pesanan.tanggal) BETWEEN "' . $tgl[0] . '" AND "' . $tgl[1] .'"'], false);
        }
        $pesanan = [];
        foreach($tmp as $v){
            if(!isset( $pesanan[$v['token']])){
                $pesanan[$v['token']] = array(
                    'atasnama' => $v['atasnama'],
                    'meja' => $v['meja'],
                    'tanggal' => $v['tanggal'],
                );
            }
        }
        response(['data' => $pesanan]);
    }

    function dashboard($jenis){
        if(!is_login('Manager')) response("Invalid akses", 403);

        $this->load->model('Order_model');
        response(['data' => $this->Order_model->dashboard($jenis)]);

    }
}