<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		must_login();
		$this->load->model('User_model');
	}

	public function index()
	{
        $data = [
            "title" => "Kelola Staff Se " . kapitalize(sessiondata('login', 'wilnama')),
			"items" => $this->User_model->userhirarkiby(['user_role="admin"'], true),
			'flash_data' => $this->session->flashdata('message')
		];
		$this->load->view("admin/v_index", $data);
	}

    function unsign($staff){
        $this->db->where('admin_gudang.admin', $staff)->delete('users', ['gudang' => null]);
		$this->session->set_flashdata('message', ['message' => 'Dikeluarkan dari gudang', 'type' => 'success']);
		redirect('admin');
	}
	function delete($staff){
        $this->db->where('users.id_user', $staff)->delete('users');
		$this->session->set_flashdata('message', ['message' => 'Dihapus', 'type' => 'success']);
		redirect('admin');
	}
}
