<?php
class Profile_model extends CI_Model
{

	public function updateProfile($profileData)
	{
		$this->db->set("user_name", $profileData["user_name"]);
		$this->db->set("user_email", $profileData["user_email"]);
		$this->db->set("user_phone", $profileData["user_phone"]);
		$this->db->set("user_address", $profileData["user_address"]);
		$this->db->where("user_email", $profileData["user_email"]);
		$this->db->update("users");
	}

	public function updatePassword($passwordHash)
	{
		$this->db->set("user_password", $passwordHash);
		$this->db->where("user_email", $this->session->userdata("user_email"));
		$this->db->update("users");
	}
}
