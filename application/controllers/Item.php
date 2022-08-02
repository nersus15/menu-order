<?php

use Spipu\Html2Pdf\Tag\Html\Em;

defined('BASEPATH') or exit('No direct script access allowed');

class Item extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'Item_model',
			'Category_model',
			'Unit_model'
		]);
		must_login();
	}

	public function index()
	{
		$data = [
			"title" => "Kelola Barang",
			"items" => $this->Item_model->getAllItems()
		];

		$this->load->view("items/v_index", $data);
	}

	public function create()
	{
		$err = null;
		$data = [
			"title" => "Tambah Data Barang",
			"categories" => $this->db->get("categories")->result_array(),
			"units" => $this->db->get("units")->result_array(),
			"item_code" => $this->Item_model->makeItemCode()
		];

		$this->_validateFormRequest();
		if ($this->form_validation->run() == FALSE) {
			$this->load->view("items/v_create", $data);
		} else {
			$this->load->model('Gudang_model');
			$gudang = $this->Gudang_model->getMyGudang();
			$itemImage = "default.jpg";
			if (!empty($_FILES["item_image"]['tmp_name'])) {
				$config = [
					"allowed_types" => "jpg|jpeg|png|bmp|gif",
					"upload_path" => "./assets/uploads/items/",
					"file_name" => $this->input->post("item_code")
				];
				$this->load->library("upload", $config);
				if ($this->upload->do_upload("item_image")) {
					$itemImage = $this->upload->data("file_name");
				}else{
					$err = array('error' => $this->upload->display_errors());
					$err = join(' ', $err);
				}
			}
			$itemData = [
				"id_category" => $this->input->post("id_category"),
				"id_unit" => $this->input->post("id_unit"),
				"item_code" => $this->input->post("item_code"),
				"item_name" => $this->input->post("item_name"),
				"item_image" => $itemImage,
				"item_stock" => $this->input->post("item_stock"),
				"item_price" => $this->input->post("item_price"),
				"item_description" => $this->input->post("item_description"),
				'gudang' => $gudang[0]['id']
			];

			$this->Item_model->insertNewItem($itemData);
			$this->session->set_flashdata('message', ['message' => 'Ditambah', 'type' => 'success']);
			redirect('item');
		}
	}

	public function update($id)
	{
		$data = [
			"title" => "Update Data Barang",
			"item" => $this->Item_model->getItemById($id),
			"categories" => $this->db->get("categories")->result_array(),
			"units" => $this->db->get("units")->result_array(),
		];

		$this->_validateFormRequest();
		if ($this->form_validation->run() == FALSE) {
			$this->load->view("items/v_update", $data);
		} else {
			$itemImage = $_FILES["item_image"];
			if (!empty($itemImage)) {
				$config = [
					"allowed_types" => "jpg|jpeg|png|bmp|gif",
					"upload_path" => "./assets/uploads/items/",
					"file_name" => $this->input->post("item_code")
				];
				$this->load->library("upload", $config);
				if ($this->upload->do_upload("item_image")) {
					$item = $this->Item_model->getItemById($id);
					$oldImage = $item["item_image"];
					if ($oldImage != "default.jpg") {
						unlink('./assets/uploads/items/' . $oldImage);
					}
					$newImage = $this->upload->data("file_name");
					$itemImage = $newImage;
				} else {
					$item = $this->Item_model->getItemById($id);
					$itemImage = $item["item_image"];
				}
			}
			$itemData = [
				"id_category" => $this->input->post("id_category"),
				"id_unit" => $this->input->post("id_unit"),
				"item_code" => $this->input->post("item_code"),
				"item_name" => $this->input->post("item_name"),
				"item_image" => $itemImage,
				"item_stock" => $this->input->post("item_stock"),
				"item_price" => $this->input->post("item_price"),
				"item_description" => $this->input->post("item_description")
			];

			$this->Item_model->updateSelectedItem($itemData, $id);
			$this->session->set_flashdata('message', ['message' => 'Diubah', 'type' => 'success']);
			redirect('item');
		}
	}

	public function delete($id)
	{

		$item = $this->Item_model->getItemById($id);
		if (file_exists('./assets/uploads/items/' . $item["item_image"]) && $item["item_image"]) {
			unlink('./assets/uploads/items/' . $item["item_image"]);
		}

		$this->Item_model->deleteSelectedItem($id);
		$this->session->set_flashdata('message', ['message' => 'Dihapus', 'type' => 'success']);
		redirect('item');
	}

	public function cekItemStock($id)
	{
		$itemId = encode_php_tags($id);
		$query = $this->Item_model->cekItemStock($itemId);
		// output_json($query); Ntah dapat function darimana, jadi ganti dulu
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($query));
	}

	private function _validateFormRequest()
	{
		$this->form_validation->set_rules('id_category', 'Kategori', 'required');
		$this->form_validation->set_rules('id_unit', 'Satuan', 'required');
		$this->form_validation->set_rules('item_code', 'Kode Barang', 'required');
		$this->form_validation->set_rules('item_name', 'Nama Barang', 'required');
		$this->form_validation->set_rules('item_stock', 'Stok Barang', 'required');
		$this->form_validation->Set_rules('item_price', 'Harga Barang', 'required');
	}
}
