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
			"title" => "Kelola Pegawai (<small> Kasir </small>)",
			"users" => $this->User_model->getby(['role' => 'Kasir'])
		];

		$this->load->view("staff/v_index", $data);
	}

	public function add($method = 'baru', $id = null){
		$this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
		$this->form_validation->set_rules('hp', 'Nomor HP', 'required|is_unique[users.hp]');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		$view = $method == 'baru' ? 'staff/v_create' : 'staff/v_update';
		if($method == 'baru'){
			$this->form_validation->set_rules('user_password', 'Password', 'required');
			$this->form_validation->set_rules('user_password_confirm', 'Konfirmasi Password', 'required|matches[user_password]');
		}

		$post = $this->input->post();
		if ($this->form_validation->run() == FALSE) {
			$this->load->view($view, [
				"title" => "Kelola Pegawai (<small> Kasir </small>)",
				'old' => $post
			]);
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
					$userAvatar = "default.png";
				}
				$post['gambar'] = $userAvatar;
			}
			$post['id'] = random(8);
			$post['registrar'] = sessiondata('login', 'id');
			$post['password'] = password_hash($post['user_password'], PASSWORD_DEFAULT);
			$post['role'] = 'Kasir';
			$post['nama_lengkap'] = $post['nama'];
			unset($post['user_password'], $post['user_password_confirm'], $post['nama']);
			
			if($method == 'baru'){
				$this->db->insert('users', $post);
				$this->session->set_flashdata('message', ['message' => 'Ditambah', 'type' => 'success']);
			}else{
				$this->db->where('id', $id)->update('users', $post);
				$this->session->set_flashdata('message', ['message' => 'Diupdate', 'type' => 'success']);
			}
			redirect('user');
		}
	}
	public function create()
	{
		$data = [
			"title" => "Kelola Pegawai (<small> Kasir </small>)",
			"users" => $this->User_model->getby(['role' => 'Kasir'])
		];
		$this->load->view("staff/v_create", $data);			
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
		$user = $this->User_model->getby(['id' => $id]);
		if (file_exists('./assets/img/avatar/users/' . $user["gambar"]) && $user["gambar"] != 'default.png') {
			unlink('./assets/img/avatar/users/' . $user["gambar"]);
		}
		$this->db->where('id', $id)->delete('users');
		$this->session->set_flashdata('message',  ['message' => 'Dihapus', 'type' => 'success']);
		redirect('user');
	}

	function baca_notif($nid){
        $this->notification->baca($nid);
	}
}
