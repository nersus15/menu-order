<?php

class Report_model extends CI_Model
{

	public function getAllSuppliers()
	{
		return $this->db->get("suppliers")->result_array();
	}

	public function getAllCustomers()
	{
		return $this->db->get("customers")->result_array();
	}

	public function viewOutcomingTransactionByDate($date)
	{
		$this->db->select("*");
		$this->db->from("outcoming_items");
		$this->db->join("items", "items.id_item = outcoming_items.id_items");
		$this->db->join("customers", "customers.id_customer = outcoming_items.id_customer");
		$this->db->where("DATE(outcoming_item_date)", $date);
		return $this->db->get()->result_array();
	}

	public function viewOutcomingTransactionByMonth($month, $year)
	{
		$this->db->select("*");
		$this->db->from("outcoming_items");
		$this->db->join("items", "items.id_item = outcoming_items.id_items");
		$this->db->join("customers", "customers.id_customer = outcoming_items.id_customer");
		$this->db->where("MONTH(outcoming_item_date)", $month);
		$this->db->where("YEAR(outcoming_item_date)", $year);
		return $this->db->get()->result_array();
	}

	public function viewOutcomingTransactionByYear($year)
	{
		$this->db->select("*");
		$this->db->from("outcoming_items");
		$this->db->join("items", "items.id_item = outcoming_items.id_items");
		$this->db->join("customers", "customers.id_customer = outcoming_items.id_customer");
		$this->db->where("YEAR(outcoming_item_date)", $year);
		return $this->db->get()->result_array();
	}

	public function viewAllOutcomingTransaction()
	{
		$this->db->select("*");
		$this->db->from("outcoming_items");
		$this->db->join("items", "items.id_item = outcoming_items.id_items");
		$this->db->join("customers", "customers.id_customer = outcoming_items.id_customer");
		return $this->db->get()->result_array();
	}

	public function yearOptionsForOutcomingTransaction()
	{
		$this->db->select("YEAR(outcoming_item_date) AS tahun");
		$this->db->from("outcoming_items");
		$this->db->order_by("YEAR(outcoming_item_date)");
		$this->db->group_by("YEAR(outcoming_item_date)");

		return $this->db->get()->result_array();
	}


	public function viewIncomingTransactionByDate($date)
	{
		$this->db->select("*");
		$this->db->from("incoming_items");
		$this->db->join("items", "items.id_item = incoming_items.id_items");
		$this->db->join("suppliers", "suppliers.id_supplier = incoming_items.id_supplier");
		$this->db->where("DATE(incoming_item_date)", $date);
		return $this->db->get()->result_array();
	}

	public function viewIncomingTransactionByMonth($month, $year)
	{
		$this->db->select("*");
		$this->db->from("incoming_items");
		$this->db->join("items", "items.id_item = incoming_items.id_items");
		$this->db->join("suppliers", "suppliers.id_supplier = incoming_items.id_supplier");
		$this->db->where("MONTH(incoming_item_date)", $month);
		$this->db->where("YEAR(incoming_item_date)", $year);

		return $this->db->get()->result_array();
	}

	public function viewIncomingTransactionByYear($year)
	{
		$this->db->select("*");
		$this->db->from("incoming_items");
		$this->db->join("items", "items.id_item = incoming_items.id_items");
		$this->db->join("suppliers", "suppliers.id_supplier = incoming_items.id_supplier");
		$this->db->where("YEAR(incoming_item_date)", $year);

		return $this->db->get()->result_array();
	}

	public function viewAllIncomingTransaction()
	{
		$this->db->select("*");
		$this->db->from("incoming_items");
		$this->db->join("items", "items.id_item = incoming_items.id_items");
		$this->db->join("suppliers", "suppliers.id_supplier = incoming_items.id_supplier");
		return $this->db->get()->result_array();
	}

	public function yearOptionsForIncomingTransaction()
	{
		$this->db->select("YEAR(incoming_item_date) AS tahun");
		$this->db->from("incoming_items");
		$this->db->order_by("YEAR(incoming_item_date)");
		$this->db->group_by("YEAR(incoming_item_date)");

		return $this->db->get()->result_array();
	}
}
