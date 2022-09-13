<?php
class Order extends CI_Controller{
    function __construct() {
        parent::__construct();

        $this->load->model('Order_model');
    }
    function index(){
        $data = [
            "title" => "Scan Untuk Memesan",
        ];

        $this->load->view("order/scan", $data);
    }
    function sign($kode = null, $token = null){
       
        $invalid = empty($kode) || empty($token);
        $bypass = false;
        if($invalid)
            show_error('Anda Mengakses Halaman Dengan Cara yang Salah', 403, 'Invalid');
    
        $bypass = $this->Order_model->cekToken($token);

        $m = $this->db->where('kode', $kode)->get('meja')->row();
        if(empty($m)){
            show_error('Kode Invalid', 403, 'Invalid');
        }
        $t = $this->db->where('token', $token)
            ->where('diakses IS NULL', null, false)
            ->get('tokens')->row();
        if(!$bypass){
            if(empty($t)){
                show_error('Token Invalid', 403, 'Invalid');
            }else{
                if($m->status == 'TERISI')
                    show_error("Meja Terisi", 403, 'Meja yang anda scan sudah terisi');
                // Cek if Expired Token
                $a = new DateTime();
                $b = new DateTime($t->dibuat);
                $diff = $a->diff($b);
        
                if ($diff->i >= 30) {
                    show_error('Token Expired', 403, 'Invalid');
                }
            }
        }
        // Tandai Meja Terisi
        $this->db->where('kode', $kode)->update('meja', ['status' => 'TERISI']);

        // Tandai Token Sudah digunakan
        $this->db->where('token', $token)->update('tokens', ['diakses' => waktu(null, MYSQL_DATE_FORMAT)]);

        // Render View
        $tmp = $this->db->get('menus')->result_array();
        $menus = [];

        foreach ($tmp as $key => $value) {
            if(isset($menus[$value['jenis']])){
                $menus[$value['jenis']][] = $value;
            }else{
                $menus[$value['jenis']] = [$value];
            }
        }
        $data = [
            "title" => "Menu Kami",
            'pesanan' => $this->Order_model->getby(['token' => $token], false, 'atasnama'),
            'menus' => $menus,
            'tmp' => $tmp,
            'meja' => $m->id,
            'token' => $token,
        ];
        $this->load->view("order/menu", $data);       
    }

    function add_token(){
        $token = [
            'token' => random(25),
            'dibuat' => waktu()
        ];
        $this->db->insert('tokens', $token);
        response($token);
    }

    function create(){
        $post = $this->input->post();
        $this->Order_model->create($post['pesanan'], $post['atasnama'], $post['meja'], $post['token']);
    }

    function summary($token){
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
            'status' => $tmp[0]['status']
        ];
        $this->load->view("order/summary", $data);       
    }

    function realtime(){
        if(!is_login('Kasir')) redirect('dashboard');

        $data = [
            "title" => "Pesanan Aktif (<small> Reload setiap 5 detik </small>)" ,
        ];
        $this->load->view("order/realtime", $data);    
    }
    function list(){
        if(!is_login('Manager')) redirect('order/realtime');

        $data = [
            "title" => "Rekap Penjualan" ,
        ];
        $this->load->view("order/rekap", $data);    
    }
    function pay($token, $meja){
        if(!is_login()) response('Anda belum login', 403);

        $this->Order_model->pay($token, $meja);
        response('Berhasil');
    }

    function today(){
        if(!is_login()) redirect('auth');
        $tmp = $tmp = $this->Order_model->getby(['tanggal' => waktu(null, MYSQL_DATE_FORMAT), 'pesanan.status' => 'CLOSE']);
        $pesanan = [];
        foreach($tmp as $v){
            if(!isset( $pesanan[$v['token']])){
                $pesanan[$v['token']] = array(
                    'atasnama' => $v['atasnama'],
                    'meja' => $v['meja'],
                );
            }
        }
        $data = [
            "title" => "Rekap Pesanan Hari ini (<small> Pesanan yang sudah selesai </small>)" ,
            'orders' => $pesanan,
        ];
        $this->load->view("order/today", $data);    
    }
}
