<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gudang extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		must_login();
		$this->load->model('Gudang_model');
	}

	public function index()
	{
        $data = [
            "title" => "Kelola Gudang Se " . kapitalize(sessiondata('login', 'wilnama')),
			"items" => $this->Gudang_model->getbyuser(),
			'flash_data' => $this->session->flashdata('message')
		];

		$this->load->view("gudang/v_index", $data);
	}
	function create(){
		$this->load->model('User_model');
		$wilayah = $this->User_model->gethirarkiWilayah();
		$admin = $this->User_model->userhirarkiby(['user_role' => 'admin']);
		$staff = $this->User_model->userhirarkiby(['user_role' => 'staff']);
		$data = [
            "title" => "Kelola Gudang Se " . kapitalize(sessiondata('login', 'wilnama')),
			"items" => $this->Gudang_model->getbyuser(),
			'wilayah' => $wilayah,
			'admin' => $admin,
			'staff' => $staff,
			'flash_data' => $this->session->flashdata('message')
		];
		$this->add_cachedJavascript('js/custom/form-gudang');

		$err = null;
		
		$this->form_validation->set_rules('nama', 'Satuan', 'required');
		$this->form_validation->set_rules('wilayah', 'Kode Barang', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('gudang/v_create', $data);
		} else {
			$post = $this->input->post();
			$dataStaff = [];
			$dataAdmin = [];
			$dataGudang = [
				'id' => random(8),
				'nama' => $post['nama'],
				'wilayah' => $post['wilayah'],
				'alamat' => $post['alamat'],
			];
			if(isset($post['staff']) && !empty($post['staff'])){
				foreach($post['staff'] as $staff)
					$dataStaff[] = $staff;
			}
			if(isset($post['admin']) && !empty($post['admin'])){
				foreach($post['admin'] as $admin)
					$dataAdmin[] = array(
						'admin' => $admin,
						'gudang' => $dataGudang['id']
					);
			}
			$this->Gudang_model->create($dataGudang);
			if(!empty($dataAdmin))
				$this->Gudang_model->insertAdmin($dataAdmin, null, 'gudang/create');
			if(!empty($dataStaff))
				$this->Gudang_model->insertStaff($dataStaff, $dataGudang['id'], 'gudang/create');

			$this->session->set_flashdata('message', ['message' => 'Ditambah', 'type' => 'success']);
			redirect('gudang');
		}
	}

	function delete($gudang){
		$this->Gudang_model->delete($gudang, 'gudang');
		$this->session->set_flashdata('message', ['message' => 'Dihapus', 'type' => 'success']);
		redirect('gudang');
	}

	function detail($gudang){
		$idgudang = [$gudang];
		$data = [
            "title" => "Kelola Gudang Se " . kapitalize(sessiondata('login', 'wilnama')),
			"transaksi" => $this->Gudang_model->getTransaksi($idgudang),
			'gudang' => $this->Gudang_model->getBy(['gudang.id' => $gudang]),
			'flash_data' => $this->session->flashdata('message')
		];

		$this->load->view("gudang/detail", $data);
	}
}
