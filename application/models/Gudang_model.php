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
    function getBy($where = null){
        $q = $this->db->select('gudang.*, wilayah.level as level_wilayah_gudang, wilayah.nama as nama_wilayah_gudang')
            ->join('wilayah', 'wilayah.id = gudang.wilayah');
        if(!empty($where)){
            foreach($where as $k => $v){
				if(is_numeric($k)){
					$q->where($v, null, false);
				}else{
					$q->where($k, $v);
				}
			}
        }
        $staff = [];
        $admin = [];
        $items = [];
        $gudang = $q->get('gudang')->result_array();
        $tmp = $gudang;
        foreach($gudang as $k => $v){
            $staff = $this->db->select('users.*, wilayah.nama as wilayah_kerja_staff, wilayah.level as level_wilayah_staff')
                ->where('gudang', $v['id'])
                ->join('wilayah', 'wilayah.id = users.wilayah')
                ->get('users')->result_array();
            $admin = $this->db->select('users.*, wilayah.nama as wilayah_kerja_admin, wilayah.level as level_wilayah_admin')
                 ->where('admin_gudang.gudang', $v['id'])
                 ->join('admin_gudang', 'admin_gudang.admin = users.id_user')
                 ->join('wilayah', 'wilayah.id = users.wilayah')
                 ->get('users')->result_array();
            $items = $this->db->select('*')
                ->where('items.gudang', $v['id'])
                ->join('categories', 'categories.id_category = items.id_category')
                ->join('units', 'units.id_unit = items.id_unit')
                ->get('items')->result_array();
            $tmp[$k]['staff'] = $staff;
            $tmp[$k]['admin'] = $admin;
            $tmp[$k]['items'] = $items;
        }
        
        
        return $tmp;
    }
    function create($data){
        try {
            $this->db->insert('gudang', $data);
        } catch (\Throwable $th) {
            $this->session->set_flashdata('message', ['message' => $th::getMessage(), 'type' => 'danger']);
            redirect('gudang/create');
        }
    }

    function update($data, $idgudang){
        extract($data);
        try {
            $this->db->where('gudang.id', $idgudang)->update('gudang', $gudang);

            if(isset($staff) && !empty($staff)){
                $this->db->where_in('users.id_user', $staff)->update('users', ['gudang' => $idgudang]);
            }else{
                $this->db->where('users.gudang', $idgudang)->update('users', ['gudang' => null]);
            }
            if(isset($admin) && !empty($admin)){
                $this->db->where('admin_gudang.gudang', $idgudang)->delete('admin_gudang');
                $tmp = [];
                foreach($admin as $id){
                    $tmp[] = array(
                        'admin' => $id,
                        'gudang' => $idgudang
                    );
                }
                $this->insertAdmin($tmp);
            }else{
                $this->db->where('admin_gudang.gudang', $idgudang)->delete('admin_gudang');
            }

        } catch (\Throwable $th) {
            $this->session->set_flashdata('message', ['message' => $th::getMessage(), 'type' => 'danger']);
            redirect('gudang/update/' . $idgudang);
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
    function hirarkiby($where = null, $reverse = false){
		$level = sessiondata('login', 'willevel');
		$wil = sessiondata('login', 'idwil');
		$q = $this->db->select('gudang.*, wilayah.nama as wilayah_gudang, wilayah.level as level_wilayah');
		if(!empty($where)){
			foreach($where as $k => $v){
				if(is_numeric($k)){
					$q->where($v, null, false);
				}else{
					$q->where($k, $v);
				}
			}
		}
		$q->join('wilayah', 'wilayah.id = gudang.wilayah');
        if(!$reverse){
            if($level == 2)
                $q->like('wilayah', substr($wil, 0, 5), 'after');
            else if($level == 3)
                $q->where('wilayah', $wil);

        }else{
            if($level == 2)
                $q->where('wilayah', substr($wil, 0, 2) . '.00.00.0000', 'after');
            else if($level == 3)
                $q->where('wilayah', substr($wil, 0, 5) . '.00.0000');
        }
		$data = $q->get('gudang')->result_array();
		

		return $data;
	}

    function getTransaksi($gudang = null){
        $query = $this->db->select("items.*, users.user_name as nama_pencatat, transaksi.*, gudang_tujuan.nama as namagudang_tujuan, wilayah_tujuan.nama namawil_tujuan, wilayah_tujuan.level as lvlwil_tujuan, gudang_asal.nama as namagudang_asal, wilayah_asal.nama namawil_asal, wilayah_asal.level as lvlwil_asal")
			->from("transaksi")
			->join("users", "users.id_user = transaksi.pencatat")
			->join("items", "items.id_item = transaksi.id_items")
			->join("gudang as gudang_tujuan", "gudang_tujuan.id = transaksi.tujuan", 'left')
			->join("wilayah as wilayah_tujuan", "wilayah_tujuan.id = gudang_tujuan.wilayah",'left')
			->join("gudang as gudang_asal", "gudang_asal.id = transaksi.gudang", 'left')
			->join("wilayah as wilayah_asal", "wilayah_asal.id = gudang_asal.wilayah",'left')
            ->order_by('id_transaksi')
            ->select('penghapus.user_name as nama_penghapus')
            ->join('users penghapus', 'penghapus.id_user = transaksi.penghapus', 'left');
		if(!empty($gudang)){
			$query->where_in('transaksi.gudang', $gudang);
		}
		return $query->get()->result_array();
    }
}
