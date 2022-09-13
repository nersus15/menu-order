<?php
    class Meja extends CI_Controller{
        function __construct() {
           parent::__construct();
           if(!is_login()) redirect('auth');
        }
        function index(){
            $data = [
                "title" => "Kelola Meja",
                "meja" => $this->db->get('meja')->result_array()
            ];
    
            $this->load->view("meja/v_index", $data);
        }

        function add(){
            require_once APPPATH.'third_party/phpqrcode/qrlib.php';
            $last = $this->db->select('id')->order_by('id', 'DESC')->get('meja')->row();
            $current = empty($last) ? 1 : $last->id + 1;
            $qrcode =  random(10);
            $codeContents = base_url('order/sign/' . $qrcode); 
            //output gambar langsung ke browser, sebagai PNG
            QRcode::png($codeContents, get_path(ASSET_PATH . 'img/qr/' . $qrcode . '.png')); 
            $data = [
                'id' => $current,
                'kode' => $qrcode,
                'status' => 'KOSONG'
            ];
            $this->db->insert('meja', $data);
            response($data);
        }
        function remove($id, $kode){
            $this->db->where('id', $id)->delete('meja');
            unlink(get_path(ASSET_PATH . 'img/qr/' . $kode . '.png'));
            response('OK');
        }
    }