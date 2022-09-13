<?php
    class Dashboard extends CI_Controller{
        function __construct()
        {
            parent::__construct();
            if(!is_login()) redirect(base_url('auth'));

            if(is_login('Kasir')) redirect('order/realtime');
        }
        function index(){
            $data = [
                "title" => "Dashboard",
                "kasir" => $this->db->where('role', 'Kasir')->get('users')->num_rows(),
                "meja" => $this->db->get('meja')->num_rows(),
                "menu" => $this->db->get('menus')->num_rows(),
                'penjualan' => $this->db->where('status', 'CLOSE')->get('pesanan')->num_rows(),
                // 'grafik' => 
            ];
    
            $this->load->view("dashboard/index", $data);
            
        }
    }