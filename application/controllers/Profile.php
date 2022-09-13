<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		must_login();
	}

	public function index()
	{
		
		$data["title"] = "Profile Saya";
		// $data['gudang'] = $this->Gudang_model->getMyGudang();

		$this->load->view('profile/v_index', $data);
	}

	public function editProfile()
	{

		$this->form_validation->set_rules("username", "Username", "required|trim");
		$this->form_validation->set_rules("nama", "Nama Lengkap", "required|trim");
		$this->form_validation->set_rules("hp", "No HP", "required|trim");
		$this->form_validation->set_rules("alamat", "Alamat", "required|trim");
		if ($this->form_validation->run() == FALSE) {
			$this->index();
		} else {
			$profileData = [
				"username" => $this->input->post("username"),
				"nama_lengkap" => $this->input->post("nama"),
				"hp" => $this->input->post("hp"),
				"alamat" => $this->input->post("alamat"),
			];
			$newSessionData = sessiondata();
			// cek jika ada gambar yang diupload
			$uploadImage = $_FILES["gambar"];
			if ($uploadImage) {
				$config["allowed_types"] = "gif|jpg|png|bmp|jpeg";
				$config["upload_path"] = "./assets/img/avatar/";
				$config['file_name'] = random(8);
				$this->load->library("upload", $config);
				// var_dump($this->upload->do_upload("gambar"));die;
				if ($this->upload->do_upload("gambar")) {
					$userSession = sessiondata();
					$oldAvatar = $userSession["gambar"];
					if ($oldAvatar != "default.png") {
						unlink('./assets/img/avatar/' . $oldAvatar);
					}
					$newAvatar = $this->upload->data("file_name");
					$newSessionData['gambar'] = $newAvatar;
					$profileData['gambar'] = $newAvatar;
				} else {
					echo $this->upload->display_errors();
				}
			}

			foreach ($profileData as $key => $value) {
				if(isset($newSessionData[$key]))
					$newSessionData[$key] = $value;
			}


			$this->db->where('id', sessiondata('login', 'id'))->update("users", $profileData);
			$this->session->set_userdata('login' . '_' . APPNAME, $newSessionData);
			$this->session->set_flashdata('message', ['message' => 'Profile Berhasil diperbarui', 'type' => 'success']);
			redirect("profile");
		}
	}

	public function changePassword()
	{

		$this->form_validation->set_rules("current_password", "Password sekarang", "required|trim");
		$this->form_validation->set_rules("new_password", "Password Baru", "required|trim|min_length[4]", [
			"min_length" => "Password minimal 4 Karakter"
		]);
		$this->form_validation->set_rules("password_confirm", "Konfirmasi Password", "required|trim|matches[new_password]", [
			"matches" => "Konfirmasi password salah"
		]);

		if ($this->form_validation->run() == FALSE) {
			$this->index();
		} else {
			$sessiondata = sessiondata();
			$currentPassword = $this->input->post("current_password");
			$newPassword = $this->input->post("new_password");
			if (!password_verify($currentPassword, $sessiondata['user_password'])) {
				$this->session->set_flashdata('message', ['message' => 'Password kamu salah', 'type' => 'danger']);
				redirect("profile");
			} else {
				if ($currentPassword == $newPassword) {
					$this->session->set_flashdata('message', ['message' => 'Password baru tidak boleh sama dengan sebelumnya', 'type' => 'danger']);
					redirect("profile");
				} else {
					// password sudah bisa diterima
					$passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
					$this->db->where('id', sessiondata('login', 'id'))->update("users", ['password' => $passwordHash]);
					$sessiondata['password'] = $passwordHash;
					$this->session->set_userdata('login' . '_' . APPNAME, $sessiondata);
					$this->session->set_flashdata('message', ['message' => 'Password Berhasil Diupdate', 'type' => 'success']);
					redirect("profile");
				}
			}
		}
	}
}
