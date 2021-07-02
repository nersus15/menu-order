<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Profile_model');

		must_login();
	}

	public function index()
	{
		$data["title"] = "Profile Saya";

		$this->load->view('profile/v_index', $data);
	}

	public function editProfile()
	{

		$this->form_validation->set_rules("user_name", "Nama", "required|trim");
		$this->form_validation->set_rules("user_email", "E-mail", "required|trim|valid_email");
		$this->form_validation->set_rules("user_phone", "No HP", "required|trim");
		$this->form_validation->set_rules("user_address", "Alamat", "required|trim");
		if ($this->form_validation->run() == FALSE) {
			$this->index();
		} else {
			$profileData = [
				"user_name" => $this->input->post("user_name"),
				"user_email" => $this->input->post("user_email"),
				"user_phone" => $this->input->post("user_phone"),
				"user_address" => $this->input->post("user_address"),
			];
			$this->session->set_userdata($profileData);
			// cek jika ada gambar yang diupload
			$uploadImage = $_FILES["user_avatar"];

			if ($uploadImage) {
				$config["allowed_types"] = "gif|jpg|png|bmp|jpeg";
				$config["upload_path"] = "./assets/uploads/users/";
				$config['file_name'] = round(microtime(true) * 1000);
				$this->load->library("upload", $config);
				if ($this->upload->do_upload("user_avatar")) {
					$userSession = $this->db->get_where("users", ["user_email" => $this->session->userdata("user_email")])->row_array();
					$oldAvatar = $userSession["user_avatar"];
					if ($oldAvatar != "default.jpg") {
						unlink('./assets/uploads/users/' . $oldAvatar);
					}
					$newAvatar = $this->upload->data("file_name");
					$this->db->set("user_avatar", $newAvatar);
					$this->session->set_userdata("user_avatar", $newAvatar);
				} else {
					echo $this->upload->display_errors();
				}
			}


			$this->Profile_model->updateProfile($profileData);
			$this->session->set_flashdata('message', '<div class="alert alert-success">Profile Berhasil diperbarui</div>');
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
			$data["user_session"] = $this->db->get_where("users", ["user_email" => $this->session->userdata("user_email")])->row_array();
			$currentPassword = $this->input->post("current_password");
			$newPassword = $this->input->post("new_password");
			if (password_verify($currentPassword, $data["users_session"]["user_password"])) {
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Password kamu salah</div>');
				redirect("profile");
			} else {
				if ($currentPassword == $newPassword) {
					$this->session->set_flashdata('message', '<div class="alert alert-danger">Password baru tidak boleh sama dengan sebelumnya</div>');
					redirect("profile");
				} else {
					// password sudah bisa diterima
					$passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

					$this->Profile_model->updatePassword($passwordHash);

					$this->session->set_flashdata('message', '<div class="alert alert-success">Silahkan login dengan password baru</div>');
					redirect("profile");
				}
			}
		}
	}
}
