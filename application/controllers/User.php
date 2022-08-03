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

	public function create($role = null)
	{
		$this->load->model('Gudang_model');
		$role = !empty($role) ? sandi($role) : $role;
		$data = [
			"title" => !in_array($role, ['admin', 'staff']) ? "Tambah User Baru"  : "Tambah User ". kapitalize($role) ." Baru",
			'gudang' => $this->Gudang_model->getbyuser(),
			'wilayah' => $this->User_model->gethirarkiWilayah(),
			'role' => $role
		];
		$this->form_validation->set_rules('user_name', 'Nama', 'required');
		$this->form_validation->set_rules('user_email', 'E-mail', 'required|valid_email|is_unique[users.user_email]');
		$this->form_validation->set_rules('user_phone', 'Nomor HP', 'required|is_unique[users.user_phone]');
		$this->form_validation->set_rules('user_address', 'Alamat', 'required');
		$this->form_validation->set_rules('wilayah', 'Wilayah Kerja', 'required');
		$this->form_validation->set_rules('user_password', 'Password', 'required');
		$this->form_validation->set_rules('user_password_confirm', 'Konfirmasi Password', 'required|matches[user_password]');
		$post = $this->input->post();
		if ($this->form_validation->run() == FALSE) {
			$this->load->view("users/v_create", $data);
		} else {
			$userAvatar = $_FILES["user_avatar"];
			if ($userAvatar) {
				$config["allowed_types"] = "jpg|jpeg|png|bmp|gif";
				$config["upload_path"] = get_path(ASSET_PATH . 'img/avatar');
				$config["file_name"] = round(microtime(true) * 1000);
				$this->load->library("upload", $config);
				// var_dump($_FILES);die;
				if ($this->upload->do_upload('user_avatar')) {
					$userAvatar = $this->upload->data('file_name');
				} else {
					$userAvatar = "default.jpg";
				}
			}
			$userData = [
				"user_name" => $post["user_name"],
				"user_email" => $post["user_email"],
				"user_phone" => $post["user_phone"],
				"user_address" => $post["user_address"],
				"user_avatar" => $userAvatar,
				'wilayah' => $post['wilayah'],
				"user_password" => password_hash($post["user_password"], PASSWORD_DEFAULT),
				"user_role" => $role
			];
			$dataGudang = [];
			if(!empty($post['gudang'])){
				$gudang = $post['gudang'];
				if($role == 'staff'){
					$userData['gudang'] = $gudang[0];
				}else{
					$dataGudang = $gudang;
				}
				

			}
			$this->User_model->insertNewUser($userData);
			if($role == 'admin' && !empty($dataGudang)){
				$newUser = $this->User_model->getLatestInsert();
				$dataAdmin = [];
				foreach($dataGudang as $v){
					$dataAdmin[] = array(
						'admin' => $newUser['id_user'],
						'gudang' => $v
					);
				}
				$this->load->model('Gudang_model');
				$this->Gudang_model->insertAdmin($dataAdmin);
			}
			$this->session->set_flashdata('message', ['message' => 'Ditambah', 'type' => 'success']);
			redirect($role);
		}
	}

	public function update($id, $role = null)
	{
		$this->load->model('Gudang_model');
		$role = !empty($role) ? sandi($role) : $role;
		$data = [
			"title" => "Update Data User " . $role ,
			"user" => $this->User_model->getUserById($id),
			'role' => $role,
			'gudang' => $this->Gudang_model->getbyuser(),
			'wilayah' => $this->User_model->gethirarkiWilayah(),
		];
		$this->form_validation->set_rules('wilayah', 'Wilayah Kerja', 'required');
		if ($this->form_validation->run() == FALSE) {
			$this->load->view("users/v_update", $data);
		} else {
			$post = $this->input->post();
			$this->db->where('id_user', $id)->update('users', ['wilayah' => $post['wilayah']]);
			if($role == 'admin'){
				$this->db->where('admin', $id)->delete('admin_gudang');
				if(!empty($post['gudang'])){
					foreach($post['gudang'] as $gudang){
						$this->db->insert('admin_gudang', ['admin' => $id, 'gudang' => $gudang]);
					}
				}
			}else{
				if(empty($post['gudang']))
					$this->db->where('id_user', $id)->update('users', ['gudang' => null]);
				else
					$this->db->where('id_user', $id)->update('users', ['gudang' => $post['gudang'][0]]);
			}

			$this->session->set_flashdata('message', ['message' => 'Diubah', 'type' => 'success']);
			redirect($role);
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

	function baca_notif($nid){
        $this->notification->baca($nid);
	}
}
