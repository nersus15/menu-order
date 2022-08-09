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
		// response($data['incoming_items']);
		$this->load->view("incoming_items/v_index", $data);
	}

	public function create()
	{
		$this->load->model('Gudang_model');
		$gudang = $this->Gudang_model->getMyGudang();
		
		$data = [
			"title" => "Tambah Data Barang Masuk",
			"incoming_item_code" => $this->IncomingItem_model->makeIncomingItemCode(),
			"items" => $this->Item_model->getAllItems(['barang_gudang.gudang' => $gudang[0]['id']]),
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

	function confirm($transaksiCode){
		$this->load->model('Outcomingitem_model');
		$transaksi = $this->Outcomingitem_model->getAllIncomingItemBy(['transaksi_code' => strtoupper($transaksiCode)]);
		if(empty($transaksi)){
			show_error("Request anda Invalid", 403, 'Transaksi Tidak ditemukan');
		}
		$data = [
			"title" => "Tindak lanjuti kiriman barang",
			"incoming_items" => $transaksi,
			'flash_data' => $this->session->flashdata('message')
		];
		// response($transaksi);
		$this->load->view("incoming_items/v_confirm", $data);
	}

	public function delete($id)
	{
		$this->IncomingItem_model->deleteSelectedIncomingItem($id);
		$this->session->set_flashdata('message', ['message' => 'Dihapus', 'type' => 'success']);
		redirect('incomingitem');
	}

	function act($act, $id){
		$post = $this->input->post();
		$barang = $this->db->select('barang_gudang.*, items.item_name, items.item_code, items.item_price')
			->join('items', 'items.id_item = barang_gudang.barang')
			->where('gudang', $post['pengirim'])
			->where('barang', $post['barang'])
			->get('barang_gudang')->row_array();
		$transaksi = $this->db->where('id_transaksi', $id)->get('transaksi')->row();
		$transaksiCode = $this->IncomingItem_model->makeIncomingItemCode();
		
		$pengirim = $this->db->where('id_user', $transaksi->pencatat)->select('users.*, gudang.nama as nama_gudang')
			->join('gudang', 'gudang.id = users.gudang')->get('users')->row_array();
		// Buat Nota
		$this->load->helper('html2pdf');
		$data = [
			'status' =>  $act == 'confirm' ? 'Verified' : 'Pending',
			'tgl' => date('d M Y', time()),
			'keterangan' => $act == 'confirm' ? null : $post['keterangan'],
			'kode_barang' => $barang['item_code'],
			'nama_barang' => $barang['item_name'],
			'jumlah_terima' => $act == 'confirm' ? $transaksi->transaksi_qty : $post['diterima'],
			'jumlah_kirim' => $transaksi->transaksi_qty,
			'tgl_kirim' => $transaksi->transaksi_date,
			'tgl_terima' => waktu(),
			'pengirim' => array(
				'nama' => kapitalize($pengirim['user_name']), 
				'alamat' => $pengirim['user_address'],
				'gudang' => kapitalize(strpos(strtolower($pengirim['nama_gudang']), 'gudang') === false ? 'Gudang ' . $pengirim['nama_gudang'] : $pengirim['nama_gudang']),
			), 
			'penerima' => array(
				'nama' =>  kapitalize(sessiondata('login', 'user_name')), 
				'alamat' => sessiondata('login', 'user_address'),
				'gudang' => kapitalize(strpos(strtolower(sessiondata('login', 'gudang')[0]['nama']), 'gudang') === false ? 'Gudanga' . sessiondata('login', 'gudang')[0]['nama'] : sessiondata('login', 'gudang')[0]['nama']),
			)
		];
		$templateNota = $this->load->view('reports/template_nota', $data, true);
		$nota = buat_pdf($templateNota, 'Nota - ' . $transaksi->id_transaksi, true);

		$newTransaksi = [
			"id_items" => $transaksi->id_items,
			"gudang_asal" => $transaksi->gudang,
			'incoming_item_code' => $transaksiCode,
			"jenis" => 'masuk',
			'gudang' => sessiondata('login', 'gudang')[0]['id'],
			'pencatat' => sessiondata('login', 'id_user'),
			'nota' => $nota,
			'incoming_price' => $barang['item_price'],
			'old_price' => $barang['item_price'],
			'old_stok' => $barang['item_stock']
		];
		$this->db->where('id_transaksi', $id)->update('transaksi', ['nota' => $nota, 'verified' => $act == 'confirm' ? 1 : 2]);
		$staffGudang = $this->db->where('gudang', $transaksi->gudang)->select('id_user')->get('users')->result();
		if($act == 'confirm'){
			$newTransaksi["incoming_item_qty"] = $transaksi->transaksi_qty;
			$newTransaksi["verified"] = 1;
			// Update Stok				
			$this->db->where('gudang', $transaksi->gudang)
				->where('barang', $transaksi->id_items)
				->update('barang_gudang', ['item_stock' => $barang['item_stock'] - $transaksi->transaksi_qty ]);

			// Create Notification
			foreach ($staffGudang as $key => $usr) {
				$this->db->insert('notifikasi', array(
					'id' => random(8),
					'jenis' => 'personal',
					'user' => $usr->id_user,
					'pesan' => "Status transaksi dengan kode <b>" . $transaksi->transaksi_code . '<b> telah dirubah menjadi <b>Pending<b> dengan keterangan sebagai berikut <br> <p>' . $post['keterangan'] . '</p>',
					'link' => 'outcomingitem'
				));
			}


		}elseif($act == 'pending'){
			$newTransaksi["incoming_item_qty"] = $post['diterima'];
			$newTransaksi["verified"] = 2;
			$newTransaksi["keterangan"] = $post['keterangan'];

			$this->db->where('gudang', $transaksi->gudang)
				->where('barang', $transaksi->id_items)
				->update('barang_gudang', ['item_stock' => $barang['item_stock'] - $post['diterima'] ]);

			// Kirimkan Notif
			foreach ($staffGudang as $key => $usr) {
				$this->db->insert('notifikasi', array(
					'id' => random(8),
					'jenis' => 'personal',
					'user' => $usr->id_user,
					'pesan' => "Status transaksi dengan kode <b>" . $transaksi->transaksi_code . '</b> telah dirubah menjadi <b>Pending<b> dengan keterangan sebagai berikut <br> <p>"' . $post['keterangan'] . '"</p>',
					'link' => 'outcomingitem'
				));
			}

		}
		$this->IncomingItem_model->insertNewIncomingItem($newTransaksi);
		$barangAdadigudang = $this->db
			->where('gudang', sessiondata('login', 'gudang')[0]['id'])
			->where('barang', $transaksi->id_items)
			->get('barang_gudang')->row_array();

		if(empty($barangAdadigudang)){
			$this->db->insert('barang_gudang', [
				'gudang' => sessiondata('login', 'gudang')[0]['id'],
				'barang' => $transaksi->id_items,
				'item_stock' => $act == 'confirm' ? $transaksi->transaksi_qty : $post['diterima']
			]);
		}else{
			$masuk = $act == 'confirm' ? $transaksi->transaksi_qty : $post['diterima'];
			$this->db->where('gudang', sessiondata('login', 'gudang')[0]['id'])
				->where('barang', $transaksi->id_items)
				->update('barang_gudang', ['item_stock' => $barangAdadigudang['item_stock'] + $masuk]);
		}
		redirect('incomingitem');
	}
	
}
