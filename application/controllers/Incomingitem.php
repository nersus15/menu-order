<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Incomingitem extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('IncomingItem_model');
		$this->load->model('Item_model');
		$this->load->model('Supplier_model');

		must_login();
	}

	public function index()
	{
		// var_dump($this->uri->segment());die;
		$this->load->model('Gudang_model');
		$gudang = $this->Gudang_model->getMyGudang();
		$data = [
			"title" => "Kelola Barang Masuk",
			"incoming_items" => $this->IncomingItem_model->getAllIncomingItems($gudang[0]['id']),
			'flash_data' => $this->session->flashdata('message')
		];

		$this->load->view("incoming_items/v_index", $data);
	}

	public function create()
	{
		$this->load->model('Gudang_model');
		$gudang = $this->Gudang_model->getMyGudang();
		
		$data = [
			"title" => "Tambah Data Barang Masuk",
			"incoming_item_code" => $this->IncomingItem_model->makeIncomingItemCode(),
			"items" => $this->Item_model->getAllItems(['items.gudang' => $gudang[0]['id']]),
			"gudang" => $this->Gudang_model->hirarkiby(null, true)
		];

		// $this->form_validation->Set_rules('gudang_asal', 'Supplier', 'required');
		$this->form_validation->set_rules('id_items', 'Item', 'required');
		$this->form_validation->set_rules('incoming_item_qty', 'Jumlah Stok Masuk', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view("incoming_items/v_create", $data);
		} else {			
			$this->IncomingItem_model->insertNewIncomingItem(array_merge($this->input->post(), [
					'gudang' => $gudang[0]['id'] ,
				]
			));
			$this->session->set_flashdata('message', ['message' => 'Ditambah', 'type' => 'success']);
			redirect('incomingitem');
		}
	}

	public function delete($id)
	{
		$this->IncomingItem_model->deleteSelectedIncomingItem($id);
		$this->session->set_flashdata('message', ['message' => 'Dihapus', 'type' => 'success']);
		redirect('incomingitem');
	}
}
