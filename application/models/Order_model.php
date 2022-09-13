<?php
    class Order_model extends CI_Model{
        
        function get_pesanan(){

        }

        function getby($where = [], $full = true, $select = 'pesanan.*'){
            $q = $this->db->select($select);
            if($full){
                $q->select('menus.nama, meja.kode as kode_meja, menus.harga, menus.jenis, menus.gambar, (menus.harga * SUM(pesanan.jumlah)) as sub_total, SUM(pesanan.jumlah) as jmlh')
                    ->join('menus', 'menus.id = pesanan.menu')
                    ->join('meja', 'meja.id = pesanan.meja')
                    ->group_by('pesanan.menu');
            }

            if(!empty($where)){
                if(is_associative($where)){
                    foreach($where as $k => $v){
                        if(!is_array($v)){
                            if(!is_numeric($k))
                                $q->where($k, $v);
                            else{
                                $q->where($v, null, false);
                            }
                        }else{
                            if($v['type'] == 'or'){
                                $q->or_where($v['field'], $v['value']);
                            }elseif($v['type'] == 'and'){
                                $q->where($v['field'], $v['value']);
                            }
                        }
                    }
                }
               
            }
            return $q->get('pesanan')->result_array();
        }
        

        function cekToken($token){
            $p = $this->db->where('status', 'OPEN')
                ->where('token', $token)
                ->where('tanggal', waktu(null, MYSQL_DATE_FORMAT))
                ->get('pesanan')->num_rows();

            return !empty($p);
        }
        function createId($jumlah = 1){
            $c = $this->db->where('tanggal', waktu(null, MYSQL_DATE_FORMAT))->get('pesanan')->num_rows();
            $kodes = [];
            for ($i=0; $i < $jumlah; $i++) { 
                if(empty($c))
                $kodes[] = "PN-" . str_replace('-', '', waktu(null, MYSQL_DATE_FORMAT)). (1 + $i);
            else
                $kodes [] = "PN-" . str_replace('-', '', waktu(null, MYSQL_DATE_FORMAT)). ($c + 1 + $i);
            }
            return $kodes;

        }
        function create($pesanan, $atasnama, $meja, $token){
            $kodes = $this->createId(count($pesanan));
            $tmp = [
                'meja' => $meja, 
                'atasnama' => $atasnama,
                'token' => $token,
                'tanggal' => waktu(null, MYSQL_DATE_FORMAT),
                'status' => 'OPEN',
            ];

            $newData = [];
            foreach($pesanan as $k => $v){
                $tmp['menu'] = $k;
                $tmp['jumlah'] = $v;
                $newData[] = $tmp;
            }
            for ($i=0; $i < count($pesanan); $i++) { 
                $newData[$i]['id'] = $kodes[$i];
            }

            try {
                $this->db->insert_batch('pesanan', $newData);
                
                if(!file_exists(get_path(ASSET_PATH . 'img/qr/' . $token . '.png'))){
                    require_once APPPATH.'third_party/phpqrcode/qrlib.php'; 
                    //output gambar langsung ke browser, sebagai PNG
                    QRcode::png($token . "=" . $meja, get_path(ASSET_PATH . 'img/qr/' . $token . '.png')); 
                }
                response("Berhasil membuat pesanan");
            } catch (\Throwable $th) {
                response("Gagal membuat pesanan", 500);
            }
        }

        function pay($token, $meja){
            if(!$this->cekToken($token)) response("Token invalid");
            
            $this->db->where('token', $token)->update('pesanan', ['status' => 'CLOSE', 'pencatat' => sessiondata('login', 'username')]);
            $this->db->where('id', $meja)->update('meja', ['status' => 'KOSONG']);
        }
        function report($where = []){
            $q = $this->db->select('pesanan.*, menus.nama, menus.harga, menus.jenis')
                ->join('menus', 'menus.id = pesanan.menu');
            if(!empty($where)){
                if(is_associative($where)){
                    foreach($where as $k => $v){
                        if(!is_array($v)){
                            if(!is_numeric($k))
                                $q->where($k, $v);
                            else{
                                $q->where($v, null, false);
                            }
                        }else{
                            if($v['type'] == 'or'){
                                $q->or_where($v['field'], $v['value']);
                            }elseif($v['type'] == 'and'){
                                $q->where($v['field'], $v['value']);
                            }
                        }
                    }
                }
                
            }
            $tmp =  $q->get('pesanan')->result_array();
            $data = [];

            foreach($tmp as $v){
                if(!isset($data[$v['token']])){
                    $data[$v['token']] = array(
                        'atasnama' => $v['atasnama'],
                        'tanggal' => $v['tanggal'],
                        'meja' => $v['meja'],
                        'pesanan' => [
                            $v['menu'] => [
                                'jumlah' => $v['jumlah'],
                                'harga' => $v['harga'],
                                'nama' => $v['nama'],
                                'sub_total' => $v['jumlah'] * $v['harga']
                            ]
                        ]
                    );
                }else{
                    if(!isset($data[$v['token']]['pesanan'][$v['menu']])){
                        $data[$v['token']]['pesanan'][$v['menu']] = [
                            'jumlah' => $v['jumlah'],
                            'id' => $v['menu'],
                            'harga' => $v['harga'],
                            'nama' => $v['nama'],
                            'sub_total' => $v['jumlah'] * $v['harga']
                        ];
                    }else{
                        $data[$v['token']]['pesanan'][$v['menu']]['jumlah'] = $data[$v['token']]['pesanan'][$v['menu']]['jumlah'] + $v['jumlah'];
                        $data[$v['token']]['pesanan'][$v['menu']]['sub_total'] = $data[$v['token']]['pesanan'][$v['menu']]['jumlah'] * $v['harga'];   
                    }
                   
                }
            }
            return $data;
        }
        function dashboard($jenis = 'bulanan'){
            $month = date('m');
            $year = date('Y');
            $curr = date('j');
            $length = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $dates = [];
            if($jenis == 'bulanan'){
                for($i = 1; $i <= $curr; $i++) $dates[] = $i;
                $tmp = $this->db->select('COUNT(DISTINCT token) jumlah, tanggal', false)
                    ->where('YEAR(tanggal) = '. $year .' AND MONTH(tanggal) = ' . $month, null, false)
                    ->where('status', 'CLOSE')
                    ->group_by('tanggal')
                    ->order_by('tanggal')
                    ->get('pesanan')->result();
                
                $data = [];
           
                foreach($dates as $d){                    
                    $date = $year . '-' . $month . '-' . $d;
                    $data[$date] = 0;
                }
                foreach($tmp as $d){
                    $dd = date('Y-m-j', strtotime($d->tanggal));
                    $data[$dd] = intval($d->jumlah);
                }
               
            }elseif($jenis == 'tahunan'){
                $length = 12;
                $daftar_bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $tmp = $this->db->select('COUNT(DISTINCT token) jumlah, MONTH(tanggal) as month', false)
                    ->where('YEAR(tanggal) = '. $year , null, false)
                    ->where('status', 'CLOSE')
                    ->group_by('tanggal')
                    ->order_by('tanggal')
                    ->get('pesanan')->result();
                $data = [];
               for ($i=1; $i <= $month; $i++) { 
                $data[$daftar_bulan[$i]] = 0;
               }
                foreach($tmp as $v){
                    $data[$daftar_bulan[$v->month]] = $data[$daftar_bulan[$v->month]] + $v->jumlah;
                }
            }
            return [
                'lng' => $length,
                'data' => $data
            ];
        }
    }