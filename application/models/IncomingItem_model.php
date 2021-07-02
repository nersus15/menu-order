<?php
class Incomingitem_model extends CI_Model
{

	public function getAllIncomingItems()
	{
		$this->db->select("*");
		$this->db->from("incoming_items");
		$this->db->join("items", "items.id_item = incoming_items.id_items");
		$this->db->join("suppliers", "suppliers.id_supplier = incoming_items.id_supplier");
		return $this->db->get()->result_array();
	}

	public function getAllIncomingItemById($id)
	{
		$this->db->select("*");
		$this->db->from("incoming_items");
		$this->db->join("items", "items.id_item = incoming_items.id_item");
		$this->db->join("suppliers", "suppliers.id_supplier = incoming_items.id_supplier");
		$this->db->where("id_incoming_items", $id);
		return $this->db->get()->row_array();
	}
	public function makeIncomingItemCode()
	{
		$this->db->select('RIGHT(incoming_items.incoming_item_code, 2) as incoming_item_code', FALSE);
		$this->db->order_by('incoming_item_code', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get("incoming_items");
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
			"id_supplier" => $incomingItemData["id_supplier"],
			"incoming_item_code" => $incomingItemData["incoming_item_code"],
			"incoming_item_qty" => $incomingItemData["incoming_item_qty"]
			
		];

		$new_price = ($incomingItemData['incoming_price'] + $incomingItemData['old_price'])/2;

		$this->db->insert("incoming_items", $insert);

		$average = ((($incomingItemData['incoming_price'] - $incomingItemData['old_price'])/ ($incomingItemData['old_stok'] + $incomingItemData["incoming_item_qty"])) * $incomingItemData['incoming_item_qty']) + $incomingItemData['old_price'];

		$this->db->where('id_item', $incomingItemData['id_items'])
			->update('items', ['item_price' => round($average)]);
	}

	public function deleteSelectedIncomingItem($id)
	{
		$this->db->where("id_incoming_items", $id);
		$this->db->delete("incoming_items");
	}
}
