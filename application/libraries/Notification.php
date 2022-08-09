<?php
class Notification {
    public $ci;
    public function __construct() {
        $this->ci =& get_instance();
    }
    public function getAll() {
        $tmp = [];
        $q = $this->ci->db->select('*')
            ->from('notifikasi');

        if(is_login()){
            $q->where("(jenis = 'global' and role = '". sessiondata('login', 'user_role') ."')")
                ->or_where("(jenis = 'personal' and user='" . sessiondata('login', 'id_user') . "')")
                ->order_by('dibuat', 'DESC');
        }
        $tmp = $q->get()->result(); 
       
        return $tmp;
            
    }

    function create($data, $batch = false){
        foreach($data as $d){
            $d['pembuat'] = is_login() ? sessiondata('login', 'id_user') : null;
            $this->ci->db->insert('notifikasi', $d);
        }
    }

    function baca($nid){
        $dibaca = waktu();
        if(is_string($nid)) $nid = array($nid);
        $this->ci->db->where_in('id', $nid)->update('notifikasi', ['dibaca' => $dibaca]);
        response(['dibaca' => $dibaca]);
    }
}