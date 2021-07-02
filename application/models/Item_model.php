<?php
class Item_model extends CI_Model
{

	public function getAllItems()
	{
		$this->db->select("*");
		$this->db->from("items");
		$this->db->join("categories", "categories.id_category = items.id_category");
		$this->db->join("units", "units.id_unit = items.id_unit");
		return $this->db->get()->result_array();
	}

	public function getItemById($id)
	{
		$this->db->select("*");
		$this->db->from("items");
		$this->db->join("categories", "categories.id_category = items.id_category");
		$this->db->join("units", "units.id_unit = items.id_unit");
		$this->db->where("id_item", $id);
		return $this->db->get()->row_array();
	}

	public function insertNewItem($itemData)
	{
		$this->db->insert("items", $itemData);
	}

	public function updateSelectedItem($itemCode, $id)
	{
		$this->db->set("id_category", $itemCode["id_category"]);
		$this->db->set("id_unit", $itemCode["id_unit"]);
		$this->db->set("item_code", $itemCode["item_code"]);
		$this->db->set("item_name", $itemCode["item_name"]);
		$this->db->set("item_image", $itemCode["item_image"]);
		$this->db->set("item_stock", $itemCode["item_stock"]);
		$this->db->set("item_price", $itemCode["item_price"]);
		$this->db->set("item_description", $itemCode["item_description"]);
		$this->db->where("id_item", $id);
		$this->db->update("items");
	}

	public function deleteSelectedItem($id)
	{
		$item = $this->Item_model->getItemById($id);
		if (file_exists('./assets/uploads/items/' . $item["item_image"]) && $item["item_image"] != "default.png") {
			unlink('./assets/uploads/items/' . $item["item_image"]);
		}
		$this->db->where("id_item", $id);
		$this->db->delete("items");
	}

	public function makeItemCode()
	{
		$this->db->select('RIGHT(items.item_code, 2) as item_code', FALSE);
		$this->db->order_by('item_code', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get("items");
		if ($query->num_rows() <> 0) {
			$data = $query->row();
			$itemCode = intval($data->item_code) + 1;
		} else {
			$itemCode = 1;
		}
		$date = date('dmY');
		$limit = str_pad($itemCode, 3, "0", STR_PAD_LEFT);
		$showCode = "BRG" . $date . $limit;
		return $showCode;
	}

	public function cekItemStock($itemId)
	{
		$this->db->join("units", "items.id_unit = units.id_unit");
		return $this->db->get_where("items", ["id_item" => $itemId])->row_array();
	}
}
