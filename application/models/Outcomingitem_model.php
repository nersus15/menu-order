<?php
class Outcomingitem_model extends CI_Model
{

	public function getAllOutcomingItems($gudang = null, $tampilkandihapus = false)
	{
		$query = $this->db->select("items.*, users.user_name as nama_pencatat, transaksi.*, gudang.nama as namagudang, wilayah.nama namawil, wilayah.level as lvlwil")
			->from("transaksi")
			->join("users", "users.id_user = transaksi.pencatat")
			->join("items", "items.id_item = transaksi.id_items")
			->join("gudang", "gudang.id = transaksi.tujuan", 'left')
			->join("wilayah", "wilayah.id = gudang.wilayah",'left')
			->where('jenis', 'keluar');
		if($tampilkandihapus){
			$query->select('penghapus.user_name as nama_penghapus')
				->join('users penghapus', 'penghapus.id_user = transaksi.penghapus', 'left');
		}else{
			$query->where('dihapus IS NULL', null, false);
		}
		if(!empty($gudang)){
			$query->where('transaksi.gudang', $gudang);
		}
		return $query->get()->result_array();
	}
	public function getAllIncomingItemById($id, $tampilkandihapus = false)
	{
		$q = $this->db->select("transaksi.*, items.*, users.user_name as nama_pencatat, gudang.nama as namagudang, wilayah.nama namawil, wilayah.level as lvlwil")
			->from("transaksi")
			->join("users", "users.id_user = transaksi.pencatat")
			->join("items", "items.id_item = transaksi.id_items")
			->join("gudang", "gudang.id = transaksi.gudang")
			->join("wilayah", "wilayah.id = gudang.wilayah")
			->where('jenis', 'keluar');
			if(is_string($id))
				$q->where('id_transaksi', $id);
			elseif(is_array($id))
				$q->where_in('id_transaksi', $id);
				
		if($tampilkandihapus){
			$q->select('penghapus.user_name as nama_penghapus')
				->join('users penghapus', 'penghapus.id_user = transaksi.penghapus', 'left');
		}else{
			$q->where('dihapus IS NULL', null, false);
		}
		return $q->get()->row_array();
	}
	public function getAllIncomingItemBy($where = null, $tampilkandihapus = false)
	{
		$q = $this->db->select("transaksi.*, items.*, users.user_name as nama_pencatat, gudang.nama as namagudang, wilayah.nama namawil, wilayah.level as lvlwil")
			->from("transaksi")
			->join("users", "users.id_user = transaksi.pencatat")
			->join("items", "items.id_item = transaksi.id_items")
			->join("gudang", "gudang.id = transaksi.gudang")
			->join("wilayah", "wilayah.id = gudang.wilayah")
			->where('jenis', 'keluar');
		if(!empty($where)){
			if(!empty($where)){
				foreach($where as $k => $v){
					if(is_numeric($k)){
						$q->where($v, null, false);
					}else{
						$q->where($k, $v);
					}
				}
			}
		}
				
		if($tampilkandihapus){
			$q->select('penghapus.user_name as nama_penghapus')
				->join('users penghapus', 'penghapus.id_user = transaksi.penghapus', 'left');
		}else{
			$q->where('dihapus IS NULL', null, false);
		}
		return $q->get()->result_array();
	}

	public function insertNewOutcomingItem($outcomingItemData)
	{
		$this->db->insert("transaksi", $outcomingItemData);
	}

	public function deleteSelectedOutcomingItem($id)
	{
		$transaksi = $this->db->get_where('transaksi',"id_transaksi = '$id'" )->row_array();
		$item = $this->db->get_where('items',"id_item = " . $transaksi['id_items'])->row_array();
		$this->db->where('id_item', $transaksi['id_items'])->update('items', ['item_stock' => $item['item_stock'] + $transaksi['transaksi_qty']]);

		$this->db->where("id_transaksi", $id);
		$this->db->update("transaksi", array('dihapus' => waktu(), 'penghapus' => sessiondata('login', 'id_user')));
	}

	public function makeOutcomingItemCode()
	{
		$this->db->select('RIGHT(transaksi.transaksi_code, 2) as outcoming_item_code', FALSE);
		$this->db->order_by('outcoming_item_code', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get("transaksi");
		if ($query->num_rows() <> 0) {
			$data = $query->row();
			$itemCode = intval($data->outcoming_item_code) + 1;
		} else {
			$itemCode = 1;
		}
		$date = date('dmY');
		$limit = str_pad($itemCode, 3, "0", STR_PAD_LEFT);
		$showCode = "TRX-K" . $date . $limit;
		return $showCode;
	}
}
