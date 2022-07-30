<?php
class Gudang_model extends CI_Model
{

	function getbyuser(){
        $user = sessiondata('login', 'id_user');
        $wilayah = sessiondata('login', 'idwil');
        $level = sessiondata('login', 'willevel');

        $prefwil = null;
        if($level == 1){
            $prefwil = substr($wilayah, 0, 2);
        }elseif($level == 2){
            $prefwil = substr($wilayah, 0, 5);
        }elseif($level == 3){
            $prefwil = $wilayah;
        }
        $query= $this->db->select('gudang.*, wilayah.level as level_wilayah, wilayah.nama as wilayah_gudang')
                ->from('gudang')
                ->join('wilayah', 'wilayah.id = gudang.wilayah')
                ->like('gudang.wilayah', $prefwil, 'after');
        $gudang = $query->get()->result();
        $tmp = $gudang;

        foreach($gudang as $k => $v){
            $admin = $this->db->select('users.*')
                ->from('users')
                ->join('admin_gudang', 'admin_gudang.admin = users.id_user')
                ->where('admin_gudang.gudang', $v->id)
                ->get()->result();

            $tmp[$k]->admin = $admin;

            $staff = $this->db->select('users.*')
                ->from('users')
                ->where('users.user_role', 'staff')
                ->where('users.gudang', $v->id)
                ->get()->result();

            $tmp[$k]->staff = $staff;
            
        }

        return $tmp;
    }
    function getMyGudang(){
        $userid = sessiondata('login', 'id_user');
        $userrole = sessiondata('login', 'user_role');

        $gudang = [];
        if($userrole == 'staff'){
            $gudang = $this->db->select('gudang.*, wilayah.level as level_wilayah_gudang, wilayah.nama as nama_wilayah_gudang')
                ->join('gudang', 'gudang.id = users.gudang')
                ->join('wilayah', 'wilayah.id = gudang.wilayah')
                ->where('id_user', $userid)
                ->get('users')->result_array();
        }else{
            $gudang = $this->db->select('gudang.*, wilayah.level as level_wilayah_gudang, wilayah.nama as nama_wilayah_gudang')
                ->join('admin_gudang', 'admin_gudang.admin = users.id_user')
                ->join('gudang', 'gudang.id = admin_gudang.gudang')
                ->join('wilayah', 'wilayah.id = gudang.wilayah')
                ->where('id_user', $userid)
                ->get('users')->result_array();
        }
        return $gudang;
    }
    function create($data){
        try {
            $this->db->insert('gudang', $data);
        } catch (\Throwable $th) {
            $this->session->set_flashdata('message', ['message' => $th::getMessage(), 'type' => 'danger']);
            redirect('gudang/create');
        }
    }

    function insertAdmin($data, $gudang = null, $redirect = null){
        try {
            if(!empty($gudang)){
                foreach($data as $d){
                    $this->db->where('gudang', $gudang)->update('admin_gudang', $d);
                }
            }else{
                $this->db->insert_batch('admin_gudang', $data);
            }
        } catch (\Throwable $th) {
            $this->session->set_flashdata('message', ['message' => $th::getMessage(), 'type' => 'danger']);
            if(!empty($redirect))
                redirect($redirect);
        }
    }
    function insertStaff($data, $gudang){
        try {
            $this->db->where_in('id_user', $data)->update('users', array('gudang' => $gudang));
        } catch (\Throwable $th) {
            $this->session->set_flashdata('message', ['message' => $th::getMessage(), 'type' => 'danger']);
            if(!empty($redirect))
                redirect($redirect);
        }
    }

    function delete($gudang, $redirect = null){
        try {
            $this->db->where('id', $gudang)->delete('gudang');
        } catch (\Throwable $th) {
            $this->session->set_flashdata('message', ['message' => $th::getMessage(), 'type' => 'danger']);
            if(!empty($redirect))
                redirect($redirect);
        }
    }
}
