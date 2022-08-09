<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		must_login();
	}

	public function index()
	{
		$data = [
			"title" => "Dashboard",
			"total_admins" => $this->db->get("users")->num_rows(),
			// "total_suppliers" => $this->db->get("suppliers")->num_rows(),
			// "total_customers" => $this->db->get("customers")->num_rows(),
			"total_items" => $this->db->get("items")->num_rows(),
		];
		

		$this->load->view("v_dashboard", $data);
	}
}
