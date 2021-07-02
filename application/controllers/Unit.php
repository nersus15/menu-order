<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Unit extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Unit_model');
		must_login();
	}

	public function index()
	{
		$data = [
			"title" => "Kelola Satuan",
		];

		$this->load->view('units/v_index', $data);
	}

	public function ajaxList()
	{
		$list = $this->Unit_model->getDatatables();
		$data = array();
		$no = $_POST['start'];
		$i = 1;
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $i++;
			$row[] = $unit->unit_name;
			$row[] = $unit->unit_description;
			$row[] = date('d F Y', strtotime($unit->created_at));

			$row[] = '<a class="btn btn-icon btn-warning" href="javascript:void(0)" title="Edit" onclick="editUnit(' . "'" . $unit->id_unit . "'" . ')"><i class="fas fa-pencil-alt"></i></a>
			<a class="btn btn-icon btn-danger" href="javascript:void(0)" title="Delete" onclick="deleteUnit(' . "'" . $unit->id_unit . "'" . ')"><i class="fas fa-trash"></i></a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST["draw"],
			"recordsTotal" => $this->Unit_model->countAll(),
			"recordsFiltered" => $this->Unit_model->countFiltered(),
			"data" => $data
		);
		echo json_encode($output);
	}

	public function ajaxEdit($id)
	{
		$data = $this->Unit_model->getById($id);
		echo json_encode($data);
	}

	public function ajaxAdd()
	{
		$this->_validateForm();
		$data = array(
			"unit_name" => $this->input->post("unit_name"),
			"unit_description" => $this->input->post("unit_description")
		);
		$this->Unit_model->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajaxUpdate()
	{
		$this->_validateForm();
		$data = array(
			"unit_name" => $this->input->post("unit_name"),
			"unit_description" => $this->input->post("unit_description")
		);
		$this->Unit_model->update(array('id_unit' => $this->input->post('id_unit')), $data);
		echo json_encode(array('status' => TRUE));
	}

	public function ajaxDelete($id)
	{
		$this->Unit_model->deleteById($id);
		echo json_encode(array('status' => TRUE));
	}

	private function _validateForm()
	{
		$data = array();
		$data['input_error'][] = array();
		$data['error_string'][] = array();
		$data['status'] = TRUE;

		if ($this->input->post("unit_name") == '') {
			$data['input_error'][] = 'unit_name';
			$data['error_string'][] = 'Nama Satuan harus diisi';
			$data['status'] = FALSE;
		}

		if ($data['status'] == FALSE) {
			echo json_encode($data);
			exit;
		}
	}
}
