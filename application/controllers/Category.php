<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Category_model');
		must_login();
	}

	public function index()
	{
		$data = [
			"title" => "Kelola Kategori",
			// "category_code" => $this->Category_model->makeCategoryCode()
		];

		$this->load->view('categories/v_index', $data);
	}

	public function ajaxList()
	{
		$list = $this->Category_model->getDatatables();
		$data = array();
		$no = $_POST['start'];
		$i = 1;
		foreach ($list as $category) {
			$no++;
			$row = array();
			$row[] = $i++;
			// $row[] = $category->category_code;
			$row[] = $category->category_name;
			$row[] = $category->category_description;
			$row[] = date('d F Y', strtotime($category->created_at));

			$row[] = '<a class="btn btn-icon btn-warning" href="javascript:void(0)" title="Edit" onclick="editCategory(' . "'" . $category->id_category . "'" . ')"><i class="fas fa-pencil-alt"></i></a>
			<a class="btn btn-icon btn-danger" href="javascript:void(0)" title="Delete" onclick="deleteCategory(' . "'" . $category->id_category . "'" . ')"><i class="fas fa-trash"></i></a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST["draw"],
			"recordsTotal" => $this->Category_model->countAll(),
			"recordsFiltered" => $this->Category_model->countFiltered(),
			"data" => $data
		);
		echo json_encode($output);
	}

	public function ajaxEdit($id)
	{
		$data = $this->Category_model->getById($id);
		echo json_encode($data);
	}

	public function ajaxAdd()
	{
		$this->_validateForm();
		$this->load->model('Gudang_model');
		$gudang = $this->Gudang_model->getMyGudang();
		if(empty($gudang)){
			response("Anda tidak memiliki gudang");
		}else{
			$data = array(
				// "category_code" => $this->input->post("category_code"),
				"category_name" => $this->input->post("category_name"),
				"category_description" => $this->input->post("category_description")
			);
			$this->Category_model->save($data);
			echo json_encode(array("status" => TRUE));
		}
		
	}

	public function ajaxUpdate()
	{
		$this->_validateForm();
		$data = array(
			// "category_code" => $this->input->post("category_code"),
			"category_name" => $this->input->post("category_name"),
			"category_description" => $this->input->post("category_description")
		);
		$this->Category_model->update(array('id_category' => $this->input->post('id_category')), $data);
		echo json_encode(array('status' => TRUE));
	}

	public function ajaxDelete($id)
	{
		$this->Category_model->deleteById($id);
		echo json_encode(array('status' => TRUE));
	}

	private function _validateForm()
	{
		$data = array();
		$data['input_error'][] = array();
		$data['error_string'][] = array();
		$data['status'] = TRUE;

		if ($this->input->post("category_name") == '') {
			$data['input_error'][] = 'category_name';
			$data['error_string'][] = 'Nama Kategori harus diisi';
			$data['status'] = FALSE;
		}

		if ($data['status'] == FALSE) {
			echo json_encode($data);
			exit;
		}
	}
}
