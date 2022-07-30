<?php

class User_model extends CI_Model
{

	public function getAllUsers()
	{
		return $this->db->get("users")->result_array();
	}

	public function getUserById($id)
	{
		$user = $this->db->get_where("users", ["id_user" => $id])->row_array();
		$gudang = [];
		if($user['user_role'] == 'admin'){
			$gudang = $this->db->select('gudang.id')
				->join('admin_gudang', 'admin_gudang.admin = users.id_user')
				->join('gudang', 'admin_gudang.gudang = gudang.id')
				->where('id_user', $user['id_user'])
				->get('users')->result_array();

			if(!empty($gudang)){
				$gudang = array_map(function($arr) {
					return $arr['id'];
				}, $gudang);
			}
			
		}else{
			$gudang =  explode(';', $user['gudang']);
		}
		$user['gudang'] = $gudang;
		return $user;
	}

	public function insertNewUser($userData)
	{
		$this->db->insert("users", $userData);
	}
	function getLatestInsert(){
		return $this->db->select('*')->order_by('id_user', 'DESC')->get('users')->row_array();
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
	function getby($where = null){

	}
	function userhirarkiby($where = null, $loadGudang = false){
		$level = sessiondata('login', 'willevel');
		$wil = sessiondata('login', 'idwil');
		$q = $this->db->select('users.*, wilayah.nama as nama_wilayah, wilayah.level as level_wilayah');
		if(!empty($where)){
			foreach($where as $k => $v){
				if(is_numeric($k)){
					$q->where($v, null, false);
				}else{
					$q->where($k, $v);
				}
			}
		}
		$q->join('wilayah', 'wilayah.id = users.wilayah');
		if($level == 2)
			$q->like('wilayah', substr($wil, 0, 5), 'after');
		else if($level == 3)
			$q->where('wilayah', $wil);
		$data = $q->get('users')->result_array();
		if($loadGudang){
			foreach($data as $k => $v){
				$gudang = [];

				if($v['user_role'] == 'staff'){
					$gudang = $this->db->select('gudang.*, wilayah.nama as wilayah_gudang, wilayah.level as level_wil_gudang')
						->join('wilayah', 'wilayah.id = gudang.wilayah')
						->where('gudang.id', $v['gudang'])->get('gudang')->result_array();
				}else{
					$gudang = $this->db->select('gudang.*, wilayah.nama as wilayah_gudang, wilayah.level as level_wil_gudang')
						->join('wilayah', 'wilayah.id = gudang.wilayah')
						->join('admin_gudang', 'admin_gudang.gudang = gudang.id')
						->where('admin_gudang.admin', $v['id_user'])
						->get('gudang')->result_array();
				}

				$data[$k]['gudang'] = $gudang;					
			}
		}

		return $data;
	}
	function gethirarkiWilayah($where = null){
		$level = sessiondata('login', 'willevel');
		$wil = sessiondata('login', 'idwil');
		$q = $this->db->select('id, nama, level')
			->where('level <', 4, null);

		if($level == 2)
			$q->like('id', substr($wil, 0, 5), 'after');
		else if($level == 3)
			$q->where('id', $wil);
			
		if(!empty($where)){
			foreach($where as $k => $v){
				if(is_numeric($k)){
					$q->where($v, null, false);
				}else{
					$q->where($k, $v);
				}
			}
		}
		return $q->get('wilayah')->result_array();
	}
}
