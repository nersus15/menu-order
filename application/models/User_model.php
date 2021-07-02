<?php

class User_model extends CI_Model
{

	public function getAllUsers()
	{
		return $this->db->get("users")->result_array();
	}

	public function getUserById($id)
	{
		return $this->db->get_where("users", ["id_user" => $id])->row_array();
	}

	public function insertNewUser($userData)
	{
		$this->db->insert("users", $userData);
	}

	public function updateSelectedUser($userData, $id)
	{
		$this->db->set('user_name', $userData["user_name"]);
		$this->db->set("user_email", $userData["user_email"]);
		$this->db->set("user_phone", $userData["user_phone"]);
		$this->db->set("user_address", $userData["user_address"]);
		$this->db->set("user_avatar", $userData["user_avatar"]);
		$this->db->set("user_role", $userData["user_role"]);
		$this->db->where("id_user", $id);
		$this->db->update("users");
	}

	public function deleteSelectedUser($id)
	{
		$this->db->where("id_user", $id);
		$this->db->delete("users");
	}
}
