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
		$this->load->model('Gudang_model');
		$gudang = $this->Gudang_model->getMyGudang();
		if(empty($gudang)){
			$this->load->view('errors/empty_gudang', ['title'=> 'Kelola Barang Keluar']);
			// exit();
		}else{
			$data = [
				"title" => "Kelola Barang Keluar",
				"outcoming_items" => $this->Outcomingitem_model->getAllOutcomingItems($gudang[0]['id'])
			];

			$this->load->view("outcoming_items/v_index", $data);
		}
		
	}

	public function create()
	{
		$this->load->model('Gudang_model');
		$gudang = $this->Gudang_model->getMyGudang();
		if(empty($gudang)){
			$this->load->view('errors/empty_gudang', ['title'=> 'Kelola Barang Keluar']);
		}else{
			$data = [
				"title" => "Tambah Data Barang Keluar",
				"outcoming_item_code" => $this->Outcomingitem_model->makeOutcomingItemCode(),
				"items" => $this->Item_model->getAllItems(['barang_gudang.gudang' => $gudang[0]['id']]),
				"gudang" => $this->Gudang_model->hirarkiby(null, false, false)
			];
			$this->add_cachedJavascript('js/kamscore/js/Kamscore', 'file', 'head');
			$this->add_cachedJavascript('js/kamscore/js/uihelper', 'file', 'head');
	
			if($this->input->post('jenis_tujuan') == 'toko'){
				$this->form_validation->set_rules('toko', 'Toko Tujuan', 'required');
			}else{
				$this->form_validation->set_rules('tujuan', 'Gudang Tujuan', 'required');
	
			}
			$post = $this->input->post();
			$this->form_validation->set_rules('id_items[]', 'Barang', 'required');
			$this->form_validation->set_rules('outcoming_item_qty[]', 'Jumlah Barang Keluar', 'required');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view("outcoming_items/v_create", $data);
			} else {
				$post = $this->input->post();
				foreach ($post['id_items'] as $key => $value) {
					$trxs[] = 
					$outcomingItemData = [
						"id_items" => $value,
						"transaksi_code" => $post["outcoming_item_code"],
						"transaksi_qty" => $post["outcoming_item_qty"][$key],
						'jenis' => 'keluar',
						'gudang' => $gudang[0]['id'],
						'pencatat' => sessiondata('login', 'id_user'),
						'tujuan' => $post['jenis_tujuan']== 'toko' ?  (strpos(strtolower($post['toko']), 'toko') !== false ? $post['toko'] : 'Toko ' . $post['toko']) : $post['tujuan']
					];
					$this->Outcomingitem_model->insertNewOutcomingItem($outcomingItemData);
					
					if($post['jenis_tujuan'] == 'toko'){
						$this->db->where('barang', $outcomingItemData['id_items'])->where('gudang', $outcomingItemData['gudang'])->update('barang_gudang', ['item_stock' => $post['item_stock_total'][$key]]);
	
						if($post['item_stock_total'][$key] < 50){
							$barang = $this->db->select("*")->where('id_item', $outcomingItemData['id_items'])->get('items')->row();
							// Buat Notif minta barang kepada semua gudang
							$this->db->insert('notifikasi', [
								'pesan' =>(strpos(strtolower(sessiondata('login', 'gudang')['0' ]['nama']), 'gudang') === false ?  'Gudang ' . sessiondata('login', 'gudang')['0' ]['nama'] : sessiondata('login', 'gudang')['0' ]['nama']) . " Kekurangan Stok untuk barang <b>" . $barang['item_name'] . "</b>",
								'id' => random(8),
								'jenis' => 'global',
								'role' => 'staff',
								'link' => 'outcomingitem/create'
							]);
						}
					}
				}
				$this->session->set_flashdata('message', ['message' => 'Ditambah', 'type' => 'success']);
	
				if($post['jenis_tujuan'] != 'toko'){
					// TODO: Buat Notif	
	
					$staffGudang = $this->db->where('gudang', $post['tujuan'])->select('id_user')->get('users')->result();
					$gudangUser = sessiondata('login', 'gudang');
					$pesan = strpos(strtolower(sessiondata('login', 'gudang')[0]['nama']), 'gudang') === false ? 'Gudang ' : null;
					$pesan .= sessiondata('login', 'gudang')[0]['nama'] . ' - ' . $gudangUser[0]['wilayah_gudang'] . " Mengirimi anda barang sebagai berikut <ol>";
					foreach ($post['id_items'] as $key => $value) {
						$namaBarang = $this->db->where('id_item', $value)
							->join('units', 'units.id_unit = items.id_unit')
							->get('items')->row();
						$pesan .= "<li> ". $namaBarang->item_name . " - " .  $post["outcoming_item_qty"][$key] . ' ' . $namaBarang->unit_name;
					 }
					 $pesan .= '</ol>';
					foreach($staffGudang as $usr){
						$this->db->insert('notifikasi', array(
							'id' => random(8),
							'jenis' => 'personal',
							'user' => $usr->id_user,
							'pesan' => $pesan,
							'link' => 'incomingitem/confirm/' . strtolower($post["outcoming_item_code"])
						));
					}
				}
	
				redirect('outcomingitem');
			}
		}
	}

	public function delete($id)
	{
		$this->Outcomingitem_model->deleteSelectedOutcomingItem($id);
		$this->session->set_flashdata('message', ['message' => 'Dihapus', 'type' => 'success']);
		redirect('outcomingitem');
	}
}
