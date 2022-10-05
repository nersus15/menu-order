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
            $tmp = $this->Order_model->getby(['tanggal' => waktu(null, MYSQL_DATE_FORMAT), 'pesanan.status != "CLOSE"'], false);
        else
            $tmp = $this->Order_model->getby(['token' => $token]);

        $pesanan = [];
        foreach($tmp as $v){
            if(!isset( $pesanan[$v['token']])){
                $pesanan[$v['token']] = array(
                    'atasnama' => $v['atasnama'],
                    'meja' => $v['meja'],
                    'status' => $v['status']
                );
            }else if(isset($pesanan[$v['token']]) && $pesanan[$v['token']]['status'] == 'PROSES' && $v['status'] == 'OPEN'){
                $pesanan[$v['token']]['status'] = 'OPEN';
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
    function file($tipe, $file){
        // open the file in a binary mode
        $path = ASSET_PATH;
        if($tipe == 'qr')
            $path .= 'img' . DIRECTORY_SEPARATOR . 'qr' . DIRECTORY_SEPARATOR;
        $path .= $file;

        if($tipe == 'qr') $path .= '.png';
        $name = get_path($path);
        $fp = fopen($name, 'rb');

        // send the right headers
        header("Content-Type: image/png");
        header("Content-Length: " . filesize($name));

        // dump the picture and stop the script
        fpassthru($fp);
        exit;
    }
    function struk($token){
        $this->load->model('Order_model');
        $tmp = $this->Order_model->getby(['token' => $token]);
        $pesanan = [];

        foreach ($tmp as $key => $value) {
            if(isset($pesanan[$value['jenis']])){
                $pesanan[$value['jenis']][] = $value;
            }else{
                $pesanan[$value['jenis']] = [$value];
            }
        }

        $data = [
            "title" => "Pesanan Anda",
            'pesanan' => $pesanan,
            'token' => $token,
            'atasnama' => $tmp[0]['atasnama'],
            'kode_meja' => $tmp[0]['kode_meja'],
            'status' => $tmp[0]['status'],
            'tgl' => $tmp[0]['tanggal'],
        ];
        $html = $this->load->view("reports/struk", $data, true);   
        $this->load->helper('html2pdf');
		$this->load->model("Order_model");
		buat_pdf($html, "Struk Pesanan Atas Nama " . $data['atasnama'] . ' Tgl ' . $data['tgl']);

    }
}