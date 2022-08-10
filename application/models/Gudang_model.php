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
                ->where('gudang.nama != "Warehouse"', null)
                ->like('gudang.wilayah', $prefwil, 'after');
        $gudang = $query->get()->result();
        $tmp = $gudang;

        foreach($gudang as $k => $v){
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
                ->where('gudang.nama != "Warehouse"', null)
                ->where('id_user', $userid)
                ->get('users')->result_array();
        }else{
            $gudang = $this->db->select('gudang.*, wilayah.level as level_wilayah_gudang, wilayah.nama as nama_wilayah_gudang')
                ->join('wilayah', 'wilayah.id = gudang.wilayah')->where('gudang.nama != "Warehouse"', null)
                ->get('gudang')->result_array();
        }
        return $gudang;
    }
    function getBy($where = null, $infoTambahan = true){
        $q = $this->db->select('gudang.*, wilayah.level as level_wilayah_gudang, wilayah.nama as nama_wilayah_gudang')
            ->join('wilayah', 'wilayah.id = gudang.wilayah')->where('gudang.nama != "Warehouse"', null);
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
        $gudang = array_filter($gudang, function($arr){
            return !empty(sessiondata('login', 'gudang')) ? $arr['id'] != sessiondata('login', 'gudang')[0]['id'] : true;
        });
        $tmp = $gudang;
        if($infoTambahan){
            foreach($gudang as $k => $v){
                $staff = $this->db->select('users.*, wilayah.nama as wilayah_kerja_staff, wilayah.level as level_wilayah_staff')
                    ->where('gudang', $v['id'])
                    ->join('wilayah', 'wilayah.id = users.wilayah')
                    ->get('users')->result_array();
                $items = $this->db->select('*')
                    ->where('barang_gudang.gudang', $v['id'])
                    ->join('barang_gudang', 'barang_gudang.barang = items.id_item')
                    ->join('categories', 'categories.id_category = items.id_category')
                    ->join('units', 'units.id_unit = items.id_unit')
                    ->get('items')->result_array();
                $tmp[$k]['staff'] = $staff;
                $tmp[$k]['admin'] = $admin;
                $tmp[$k]['items'] = $items;
            }
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

        } catch (\Throwable $th) {
            $this->session->set_flashdata('message', ['message' => $th::getMessage(), 'type' => 'danger']);
            redirect('gudang/update/' . $idgudang);
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
    function hirarkiby($where = null, $reverse = false, $hirarki = true){
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
		if($hirarki){
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
        }
        $data = $q->get('gudang')->result_array();
        $data = array_filter($data, function($arr){
            return !empty(sessiondata('login', 'gudang')) ? $arr['id'] != sessiondata('login', 'gudang')[0]['id'] : true;
        });
		return $data;
	}

    function getTransaksi($gudang = null, $where = null){
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
        if(!empty($where)){
            foreach($where as $k => $v){
				if(is_numeric($k)){
					$query->where($v, null, false);
				}else{
					$query->where($k, $v);
				}
			}
        }
		return $query->get()->result_array();
    }

    function report($jenis = 'transaksi', $sgudang = 'semua', $kelompok = null, $filter = []){
        $gudang = [];
        $tmp = $this->getMyGudang();
        if(!empty($kelompok)){
            $filter['jenis'] = $kelompok;
        }
        if(!empty($tmp)){
            $gudang = $tmp;
            foreach($tmp as $k => $v){
                if(!empty($sgudang) && $sgudang != 'semua'){
                    if ($v['id'] != $sgudang) continue;
                }
                if($jenis == 'transaksi')
                    $gudang[$k]['transaksi'] = $this->getTransaksi($v['id'], $filter);
                elseif($jenis == 'barang'){
                    $q = $this->db->select('*, barang_gudang.gudang as idgudang')
                        ->where('barang_gudang.gudang', $v['id'])
                        ->join('barang_gudang', 'barang_gudang.barang = items.id_item')
                        ->join('categories', 'categories.id_category = items.id_category')
                        ->join('units', 'units.id_unit = items.id_unit');
                    if(!empty($filter)){
                        foreach($filter as $kolom => $nilai){
                            if(is_numeric($kolom)){
                                $q->where($nilai, null, false);
                            }else{
                                $q->where($kolom, $nilai);
                            }
                        }
                    }
                    $gudang[$k]['items'] = $q->get('items')->result_array();
                }
            }
        }

        return $gudang;
    }
    function warehouse(){
		return $this->db->where('nama', 'Warehouse')->where('wilayah', '52.00.00.0000')->get('gudang')->row_array();
	}
}
