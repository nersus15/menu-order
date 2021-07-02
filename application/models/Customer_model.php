<?php
class Customer_model extends CI_Model
{

	public function getAllCustomers()
	{
		return $this->db->get("customers")->result_array();
	}

	public function getCustomerById($id)
	{
		return $this->db->get_where("customers", ["id_customer" => $id])->row_array();
	}

	public function insertNewCustomer($customerData)
	{
		$this->db->insert("customers", $customerData);
	}

	public function updateSelectedCustomer($customerData, $id)
	{
		$this->db->set("customer_code", $customerData["customer_code"]);
		$this->db->set("customer_name", $customerData["customer_name"]);
		$this->db->set("customer_email", $customerData["customer_email"]);
		$this->db->set("customer_phone", $customerData["customer_phone"]);
		$this->db->set("customer_address", $customerData["customer_address"]);
		$this->db->where("id_customer", $id);
		$this->db->update("customers", $customerData);
	}

	public function deleteSelectedCustomer($id)
	{
		$this->db->where("id_customer", $id);
		$this->db->delete("customers");
	}

	public function makeCustomerCode()
	{
		$this->db->select('RIGHT(customers.customer_code, 2) as customer_code', FALSE);
		$this->db->order_by('customer_code', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get("customers");
		if ($query->num_rows() <> 0) {
			$data = $query->row();
			$customerCode = intval($data->customer_code) + 1;
		} else {
			$customerCode = 1;
		}
		$date = date('dmY');
		$limit = str_pad($customerCode, 3, "0", STR_PAD_LEFT);
		$showCode = "CUS" . $date . $limit;
		return $showCode;
	}
}
