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
        $query= $this->db->select('gudang.*, wilayah.nama as wilayah_gudang')
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
}
