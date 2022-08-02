<?php
class Incomingitem_model extends CI_Model
{

	public function getAllIncomingItems($gudang = null, $tampilkandihapus = false)
	{
		$q = $this->db->select("users.user_name as nama_pencatat, transaksi.*, items.*, gudang.nama as namagudang, wilayah.nama namawil, wilayah.level as lvlwil")
			->from("transaksi")
			->join("users", "users.id_user = transaksi.pencatat")
			->join("items", "items.id_item = transaksi.id_items")
			->join("gudang", "gudang.id = transaksi.gudang_asal")
			->join("wilayah", "wilayah.id = gudang.wilayah")
			->where('jenis', 'masuk');
		if($tampilkandihapus){
			$q->select('penghapus.user_name as nama_penghapus')
				->join('users penghapus', 'penghapus.id_user = transaksi.penghapus', 'left');
		}else{
			$q->where('dihapus IS NULL', null, false);
		}
		if(!empty($gudang))
			$q->where('transaksi.gudang', $gudang);

		return $q->get()->result_array();
	}

	public function getAllIncomingItemById($id, $tampilkandihapus = false)
	{
		$q = $this->db->select("transaksi.*, items.*, users.user_name as nama_pencatat, gudang.nama as namagudang, wilayah.nama namawil, wilayah.level as lvlwil")
			->from("transaksi")
			->join("users", "users.id_user = transaksi.pencatat")
			->join("items", "items.id_item = transaksi.id_items")
			->join("gudang", "gudang.id = transaksi.gudang_asal")
			->join("wilayah", "wilayah.id = gudang.wilayah")
			->where('jenis', 'masuk')
			->where('id_transaksi', $id);
		if($tampilkandihapus){
			$q->select('penghapus.user_name as nama_penghapus')
				->join('users penghapus', 'penghapus.id_user = transaksi.penghapus', 'left');
		}else{
			$q->where('dihapus IS NULL', null, false);
		}
		return $q->get()->row_array();
	}
	public function makeIncomingItemCode()
	{
		$this->db->select('RIGHT(transaksi.transaksi_code, 2) as incoming_item_code', FALSE);
		$this->db->order_by('incoming_item_code', 'DESC');
		$this->db->limit(1);
		$this->db->where('jenis', 'masuk');
		
		$query = $this->db->get("transaksi");
		if ($query->num_rows() > 0) {
			$data = $query->row();
			$itemCode = intval($data->incoming_item_code) + 1;
		} else {
			$itemCode = 1;
		}
		$date = date('dmY');
		$limit = str_pad($itemCode, 3, "0", STR_PAD_LEFT);
		$showCode = "TRX-M" . $date . $limit;
		return $showCode;
	}

	public function insertNewIncomingItem($incomingItemData)
	{
		$insert = [
			"id_items" => $incomingItemData["id_items"],
			"gudang_asal" => $incomingItemData["gudang_asal"],
			"transaksi_code" => $incomingItemData["incoming_item_code"],
			"transaksi_qty" => $incomingItemData["incoming_item_qty"],
			"jenis" => 'masuk',
			'gudang' => $incomingItemData['gudang'],
			'pencatat' => sessiondata('login', 'id_user')
		];

		$new_price = ($incomingItemData['incoming_price'] + $incomingItemData['old_price'])/2;

		$this->db->insert("transaksi", $insert);

		$average = ((($incomingItemData['incoming_price'] - $incomingItemData['old_price'])/ ($incomingItemData['old_stok'] + $incomingItemData["incoming_item_qty"])) * $incomingItemData['incoming_item_qty']) + $incomingItemData['old_price'];

		$this->db->where('id_item', $incomingItemData['id_items'])
			->update('items', ['item_price' => round($average), 'item_stock' =>  $incomingItemData['item_stock_total']]);
	}

	public function deleteSelectedIncomingItem($id)
	{
		$transaksi = $this->db->get_where('transaksi',"id_transaksi = '$id'" )->row_array();
		$item = $this->db->get_where('items',"id_item = " . $transaksi['id_items'])->row_array();
		$this->db->where('id_item', $transaksi['id_items'])->update('items', ['item_stock' => $item['item_stock'] - $transaksi['transaksi_qty']]);

		$this->db->where("id_transaksi", $id);
		$this->db->update("transaksi", array('dihapus' => waktu(), 'penghapus' => sessiondata('login', 'id_user')));
	}
}
