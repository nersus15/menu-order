<?php

class Outcomingitem extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Outcomingitem_model');
		$this->load->model('Item_model');
		$this->load->model('Customer_model');

		must_login();
	}

	public function index()
	{
		$data = [
			"title" => "Kelola Barang Keluar",
			"outcoming_items" => $this->Outcomingitem_model->getAllOutcomingItems()
		];

		$this->load->view("outcoming_items/v_index", $data);
	}

	public function create()
	{
		$this->load->model('Gudang_model');
		$gudang = $this->Gudang_model->getMyGudang();
		$data = [
			"title" => "Tambah Data Barang Keluar",
			"outcoming_item_code" => $this->Outcomingitem_model->makeOutcomingItemCode(),
			"items" => $this->Item_model->getAllItems(['items.gudang' => $gudang[0]['id']]),
			"gudang" => $this->Gudang_model->hirarkiby(null)
		];
		if($this->input->post('jenis_tujuan') == 'toko'){
			$this->form_validation->set_rules('toko', 'Toko Tujuan', 'required');
		}else{
			$this->form_validation->set_rules('tujuan', 'Gudang Tujuan', 'required');

		}
		$this->form_validation->set_rules('id_items', 'Barang', 'required');
		$this->form_validation->set_rules('outcoming_item_qty', 'Jumlah Barang Keluar', 'required');
		if ($this->form_validation->run() == FALSE) {
			$this->load->view("outcoming_items/v_create", $data);
		} else {
			$outcomingItemData = [
				"id_items" => $this->input->post("id_items"),
				"transaksi_code" => $this->input->post("outcoming_item_code"),
				"transaksi_qty" => $this->input->post("outcoming_item_qty"),
				'jenis' => 'keluar',
				'gudang' => $gudang[0]['id'],
				'pencatat' => sessiondata('login', 'id_user'),
				'tujuan' => $this->input->post('jenis_tujuan') == 'toko' ?  $this->input->post('toko') : $this->input->post('tujuan')
			];
			$this->Outcomingitem_model->insertNewOutcomingItem($outcomingItemData);
			$this->db->where('id_item', $outcomingItemData['id_items'])->update('items', ['item_stock' => $this->input->post('item_stock_total')]);
			$this->session->set_flashdata('message', ['message' => 'Ditambah', 'type' => 'success']);
			redirect('outcomingitem');
		}
	}

	public function delete($id)
	{
		$this->Outcomingitem_model->deleteSelectedOutcomingItem($id);
		$this->session->set_flashdata('message', ['message' => 'Dihapus', 'type' => 'success']);
		redirect('outcomingitem');
	}
}
