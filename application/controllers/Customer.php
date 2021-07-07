<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Customer_model');
		must_login();
	}

	public function index()
	{
		$data = [
			"title" => "Kelola Customer",
			"customers" => $this->Customer_model->getAllCustomers(),
		];

		$this->load->view("customers/v_index", $data);
	}

	public function create()
	{
		$data = [
			"title" => "Tambah Data Customer",
			"customer_code" => $this->Customer_model->makeCustomerCode()
		];

		$this->_validateForm();
		if ($this->form_validation->run() == FALSE) {
			$this->load->view("customers/v_create", $data);
		} else {

			$customerData = [
				"customer_code" => $this->input->post("customer_code"),
				"customer_name" => $this->input->post("customer_name"),
				"customer_email" => $this->input->post("customer_email"),
				"customer_phone" => $this->input->post("customer_phone"),
				"customer_address" => $this->input->post("customer_address")
			];

			$this->Customer_model->insertNewCustomer($customerData);
			$this->session->set_flashdata('message', ['message' => 'Ditambah', 'type' => 'success']);
			redirect('customer');
		}
	}

	public function update($id)
	{
		$data = [
			"title" => "Ubah Data Customer",
			"customer" => $this->Customer_model->getCustomerById($id)
		];

		$this->_validateForm();
		if ($this->form_validation->run() == FALSE) {
			$this->load->view("customers/v_update", $data);
		} else {

			$customerData = [
				"customer_code" => $this->input->post("customer_code"),
				"customer_name" => $this->input->post("customer_name"),
				"customer_email" => $this->input->post("customer_email"),
				"customer_phone" => $this->input->post("customer_phone"),
				"customer_address" => $this->input->post("customer_address")
			];

			$this->Customer_model->updateSelectedCustomer($customerData, $id);
			$this->session->set_flashdata('message', ['message' => 'Diubah', 'type' => 'success']);
			redirect('customer');
		}
	}

	public function delete($id)
	{

		$this->Customer_model->deleteSelectedCustomer($id);
		$this->session->set_flashdata('message', 'Dihapus');
		redirect('customer');
	}

	private function _validateForm()
	{
		$this->form_validation->set_rules('customer_code', 'Kode Customer', 'required');
		$this->form_validation->set_rules('customer_name', 'Nama Customer', 'required');
		$this->form_validation->set_rules('customer_email', 'Email Customer', 'required');
		$this->form_validation->set_rules('customer_phone', 'No HP Customer', 'required');
		$this->form_validation->set_rules('customer_address', 'Alamat Customer', 'required');
	}
}
