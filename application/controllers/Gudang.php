<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gudang extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		must_login();
		$this->load->model('Gudang_model');
	}

	public function index()
	{
        $data = [
            "title" => "Kelola Gudang Se " . kapitalize(sessiondata('login', 'wilnama')),
			"items" => $this->Gudang_model->getbyuser(),
			'flash_data' => $this->session->flashdata('message')
		];

		$this->load->view("gudang/v_index", $data);
	}
}
