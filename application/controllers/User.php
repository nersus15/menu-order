<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		must_login();
		must_admin();
	}

	public function index()
	{
		$data = [
			"title" => "Kelola User",
			"users" => $this->User_model->getAllUsers()
		];

		$this->load->view("users/v_index", $data);
	}

	public function create()
	{
		$data = [
			"title" => "Tambah User Baru",
		];

		$this->form_validation->set_rules('user_name', 'Nama', 'required');
		$this->form_validation->set_rules('user_email', 'E-mail', 'required|valid_email|is_unique[users.user_email]');
		$this->form_validation->set_rules('user_phone', 'Nomor HP', 'required|is_unique[users.user_phone]');
		$this->form_validation->set_rules('user_address', 'Alamat', 'required');
		$this->form_validation->set_rules('user_role', 'Hak Akses', 'required');
		$this->form_validation->set_rules('user_password', 'Password', 'required');
		$this->form_validation->set_rules('user_password_confirm', 'Konfirmasi Password', 'required|matches[user_password]');
		if ($this->form_validation->run() == FALSE) {
			$this->load->view("users/v_create", $data);
		} else {
			$userAvatar = $_FILES["user_avatar"];
			if ($userAvatar) {
				$config["allowed_types"] = "jpg|jpeg|png|bmp|gif";
				$config["upload_path"] = "./assets/uploads/users/";
				$config["file_name"] = round(microtime(true) * 1000);
				$this->load->library("upload", $config);
				if ($this->upload->do_upload('user_avatar')) {
					$userAvatar = $this->upload->data('file_name');
				} else {
					$userAvatar = "default.jpg";
				}
			}
			$userData = [
				"user_name" => $this->input->post("user_name"),
				"user_email" => $this->input->post("user_email"),
				"user_phone" => $this->input->post("user_phone"),
				"user_address" => $this->input->post("user_address"),
				"user_avatar" => $userAvatar,
				"user_password" => password_hash($this->input->post("user_password"), PASSWORD_DEFAULT),
				"user_role" => $this->input->post("user_role")
			];

			$this->User_model->insertNewUser($userData);
			$this->session->set_flashdata('message', ['message' => 'Ditambah', 'type' => 'success']);
			redirect('user');
		}
	}

	public function update($id)
	{
		$data = [
			"title" => "Update Data User",
			"user" => $this->User_model->getUserById($id)
		];

		$this->form_validation->set_rules('user_name', 'Nama', 'required');
		$this->form_validation->set_rules('user_email', 'E-mail', 'required|valid_email');
		$this->form_validation->set_rules('user_phone', 'Nomor HP', 'required');
		$this->form_validation->set_rules('user_address', 'Alamat', 'required');
		$this->form_validation->set_rules('user_role', 'Hak Akses', 'required');
		if ($this->form_validation->run() == FALSE) {
			$this->load->view("users/v_update", $data);
		} else {
			$userAvatar = $_FILES["user_avatar"];
			if ($userAvatar) {
				$config["allowed_types"] = "jpg|jpeg|png|bmp|gif";
				$config["upload_path"] = "./assets/uploads/users/";
				$config["file_name"] = round(microtime(true) * 1000);
				$this->load->library('upload', $config);
				if ($this->upload->do_upload('user_avatar')) {
					$user = $this->User_model->getUserById($id);
					$oldAvatar = $user["user_avatar"];
					if ($oldAvatar != "default.jpg") {
						unlink('./assets/uploads/users/' . $oldAvatar);
					}
					$newAvatar = $this->upload->data("file_name");
					$userAvatar = $newAvatar;
				} else {
					$user = $this->User_model->getUserById($id);
					$userAvatar = $user["user_avatar"];
				}
			}
			$userData = [
				"user_name" => $this->input->post("user_name"),
				"user_email" => $this->input->post("user_email"),
				"user_phone" => $this->input->post("user_phone"),
				"user_address" => $this->input->post("user_address"),
				"user_avatar" => $userAvatar,
				// "user_password" => password_hash($this->input->post("user_password"), PASSWORD_DEFAULT),
				"user_role" => $this->input->post("user_role")
			];

			$this->User_model->updateSelectedUser($userData, $id);
			$this->session->set_flashdata('message', ['message' => 'Diubah', 'type' => 'success']);
			redirect('user');
		}
	}

	public function delete($id)
	{
		$user = $this->User_model->getUserById($id);
		if (file_exists('./assets/uploads/users/' . $user["user_avatar"]) && $user["user_avatar"]) {
			unlink('./assets/uploads/users/' . $user["user_avatar"]);
		}

		$this->User_model->deleteSelectedUser($id);
		$this->session->set_flashdata('message',  ['message' => 'Dihapus', 'type' => 'success']);
		redirect('user');
	}
}
