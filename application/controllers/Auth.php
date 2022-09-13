<?php

class Auth extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data["title"] = "Login";
		if(is_login()) redirect('dashboard');
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('user_password', 'Password', 'required|trim');
		if ($this->form_validation->run() == FALSE) {
			$this->load->view("auth/v_login", $data);
		} else {
			$this->_loginAction();
		}
	}

	private function _loginAction()
	{
		$username = $this->input->post("username");
		$password = $this->input->post("user_password");

		// cek apakah dengan email yang di input ada	
		$userData = $this->db->get_where("users", ["username" => $username])->row_array();
		if ($userData) {
			// cek apakah password yang dimasukkan benar
			if (password_verify($password, $userData["password"])) {
				$this->session->set_userdata('login' . '_' . APPNAME, $userData);
				redirect('dashboard');
			} else {
				$this->session->set_flashdata('message', ['message' => 'Password kamu salah', 'type' => 'danger']); 
				redirect("auth");
			}
		} else {
			// $this->session->set_flashdata('message', 'E-mail kamu salah</div>');
			$this->session->set_flashdata('message', ['message' => 'E-mail kamu salah', 'type' => 'danger']); 

			redirect("auth");
		}
	}

	public function logout()
	{
		$this->session->unset_userdata("login_" . APPNAME);
		// $this->session->set_flashdata('message', '<div class="alert alert-success">Kamu berhasil logout</div>');
		$this->session->set_flashdata('message', ['message' => 'Kamu berhasil logout', 'type' => 'success']); 
		
		redirect("auth");
	}

	public function block()
	{
		echo 'Kamu tidak memiliki hak akses';
	}
}
