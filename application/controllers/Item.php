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
		$this->load->model('Gudang_model');
		if (is_login('staff') && empty(sessiondata('login', 'gudang'))) {
			$this->load->view('errors/empty_gudang', ['title' => 'Kelola Barang']);
		} else {
			$filter = [];
			if (is_login('staff')) {
				$filter['barang_gudang.gudang'] = sessiondata('login', 'gudang')[0]['id'];
			}
			$data = [
				"title" => "Kelola Barang",
				"items" => $this->Item_model->getAllItems($filter),
				'gudang' => $this->Gudang_model->getBy(null, false)
			];

			$this->load->view("items/v_index", $data);
		}
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
			if (empty($gudang)) {
				$this->load->view('errors/empty_gudang', ['title' => 'Kelola Barang']);
			} else {
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
					} else {
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
					"item_price" => $this->input->post("item_price"),
					"item_description" => $this->input->post("item_description")
				];

				$this->Item_model->insertNewItem($itemData);
				$this->session->set_flashdata('message', ['message' => 'Ditambah', 'type' => 'success']);
				redirect('item');
			}
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
		$gudang = sessiondata('login', 'gudang')[0]['id'];
		$this->Item_model->deleteSelectedItem($id, $gudang);
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

	function kirim()
	{
		$post = $this->input->post();
		$this->load->model('IncomingItem_model');
		$this->load->model('Gudang_model');
		$warehouse = $this->Gudang_model->warehouse();
		$notifikasi = [];
		foreach ($post['gudang'] as $gudang) {
			// Notifikasi
			$pesan = 'Gudang Wrehouse - Mataram Mengirimi anda barang sebagai berikut <ol>';
			$staffGudang = $this->db->where('gudang', $gudang)->select('id_user')->get('users')->result();
			foreach ($post['barang'] as $k => $v) {
				$ada = $this->cek_sudah_ada($gudang, $v);
				if (empty($ada)) {
					$this->db->insert('barang_gudang', array(
						'gudang' => $gudang,
						'barang' => $v,
						'item_stock' => $post['jumlah'][$k],
						'ditambah' => waktu()
					));
				} else {
					$this->db->where('id', $ada['id'])->update('barang_gudang', ['item_stock' => $ada['item_stock'] + $post['jumlah'][$k]]);
				}

				$newTransaksi = [
					"id_items" => $v,
					"gudang_asal" => $warehouse['id'],
					'incoming_item_code' => $this->IncomingItem_model->makeIncomingItemCode(),
					"jenis" => 'masuk',
					'gudang' => $gudang,
					'pencatat' => sessiondata('login', 'id_user'),
					'nota' => null,
					'incoming_item_qty' => $post['jumlah'][$k],
					'verified' => 1
				];
				$this->IncomingItem_model->insertNewIncomingItem($newTransaksi);

				// Notifikasi
				$namaBarang = $this->db->where('id_item', $v)
					->join('units', 'units.id_unit = items.id_unit')
					->get('items')->row();
					;
				$pesan .= "<li> " . $namaBarang->item_name . " - " . $post['jumlah'][$k] . ' ' . $namaBarang->unit_name;
			}
			foreach ($staffGudang as $usr) {
				$this->db->insert('notifikasi', array(
					'id' => random(8),
					'jenis' => 'personal',
					'user' => $usr->id_user,
					'pesan' => $pesan,
					'link' => '#'
				));
			}
		}

		$this->session->set_flashdata('message', ['message' => 'Berhasil', 'type' => 'success']);
		redirect('item');
	}

	private function cek_sudah_ada($gudang, $barang)
	{
		$barang = $this->db->where('gudang', $gudang)
			->where('barang', $barang)
			->get('barang_gudang')->row_array();

		return $barang;
	}
	private function _validateFormRequest()
	{
		$this->form_validation->set_rules('id_category', 'Kategori', 'required');
		$this->form_validation->set_rules('id_unit', 'Satuan', 'required');
		$this->form_validation->set_rules('item_code', 'Kode Barang', 'required');
		$this->form_validation->set_rules('item_name', 'Nama Barang', 'required');
		$this->form_validation->Set_rules('item_price', 'Harga Barang', 'required');
	}
}
