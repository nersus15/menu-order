<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Supplier_model');
		must_login();
	}

	public function index()
	{
		$data = [
			"title" => "Kelola Supplier",
			"suppliers" => $this->Supplier_model->getAllSuppliers(),
		];

		$this->load->view("suppliers/v_index", $data);
	}

	public function create()
	{
		$data = [
			"title" => "Tambah Data Supplier",
			"supplier_code" => $this->Supplier_model->makeSupplierCode()
		];

		$this->_validateForm();
		if ($this->form_validation->run() == FALSE) {
			$this->load->view("suppliers/v_create", $data);
		} else {

			$supplierData = [
				"supplier_code" => $this->input->post("supplier_code"),
				"supplier_name" => $this->input->post("supplier_name"),
				"supplier_email" => $this->input->post("supplier_email"),
				"supplier_phone" => $this->input->post("supplier_phone"),
				"supplier_address" => $this->input->post("supplier_address")
			];

			$this->Supplier_model->insertNewSupplier($supplierData);
			$this->session->set_flashdata('message', 'Ditambah');
			redirect('supplier');
		}
	}

	public function update($id)
	{
		$data = [
			"title" => "Ubah Data Supplier",
			"supplier" => $this->Supplier_model->getSupplierById($id)
		];

		$this->_validateForm();
		if ($this->form_validation->run() == FALSE) {
			$this->load->view("suppliers/v_update", $data);
		} else {
			$supplierData = [
				"supplier_code" => $this->input->post("supplier_code"),
				"supplier_name" => $this->input->post("supplier_name"),
				"supplier_email" => $this->input->post("supplier_email"),
				"supplier_phone" => $this->input->post("supplier_phone"),
				"supplier_address" => $this->input->post("supplier_address")
			];

			$this->Supplier_model->updateSelectedSupplier($supplierData, $id);
			$this->session->set_flashdata('message', 'Diubah');
			redirect('supplier');
		}
	}

	public function delete($id)
	{

		$this->Supplier_model->deleteSelectedSupplier($id);
		$this->session->set_flashdata('message', 'Dihapus');
		redirect('supplier');
	}

	private function _validateForm()
	{
		$this->form_validation->set_rules('supplier_code', 'Kode Supplier', 'required');
		$this->form_validation->set_rules('supplier_name', 'Nama Supplier', 'required');
		$this->form_validation->set_rules('supplier_email', 'Email Supplier', 'required');
		$this->form_validation->set_rules('supplier_phone', 'No HP Supplier', 'required');
		$this->form_validation->set_rules('supplier_address', 'Alamat Supplier', 'required');
	}
}
