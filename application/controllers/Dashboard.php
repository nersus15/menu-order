<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		must_login();
	}

	public function index()
	{
		
		
		if(is_login('admin')){
			$data = [
				"title" => "Dashboard",
				"staff" => $this->db->where('user_role', 'staff')->get("users")->num_rows(),
				"items" => $this->db->get("items")->num_rows(),
				'gudang' => $this->db->where('nama != "Warehouse"')->get('gudang')->num_rows()
			];
			$this->load->view("admin/v_dashboard", $data);
		}elseif(is_login('staff')){
			$data = [
				"title" => "Dashboard",
				"staff" => $this->db->where('user_role', 'staff')->where('gudang', sessiondata('login', 'gudang')[0]['id'])->get("users")->num_rows(),
				"items" => $this->db->where('gudang', sessiondata('login', 'gudang')[0]['id'])->get("barang_gudang")->num_rows(),
				"transaksi" => $this->db->where('gudang', sessiondata('login', 'gudang')[0]['id'])->get("transaksi")->result_array(),
			];
			$this->load->view("v_dashboard", $data);
		}
	}
}
