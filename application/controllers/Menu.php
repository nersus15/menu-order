<?php
class Menu extends CI_Controller{
    function index(){
        $tmp = $this->db->get('menus')->result_array();
        $menus = [];

        foreach ($tmp as $key => $value) {
            if(isset($menus[$value['jenis']])){
                $menus[$value['jenis']][] = $value;
            }else{
                $menus[$value['jenis']] = [$value];
            }
        }

        $data = [
            "title" => "Kelola Menu",
            "menus" => $menus,
            "tmp" => $tmp,
        ];

        $this->load->view("menu/v_index", $data);
    }

    function create(){
        $post = $this->input->post();
        $data = $post;
        $data['id'] = random(8);
        if(isset($_FILES['gambar']) && !empty($_FILES['gambar']) && !empty($_FILES['gambar']['name'])){
            $config["allowed_types"] = "jpg|png|jpeg";
            $config["upload_path"] = "./assets/img/products/";
            $config['file_name'] = random(8);
            $this->load->library("upload", $config);
            // var_dump($this->upload->do_upload("gambar"));die;
            if ($this->upload->do_upload("gambar")) {
                $gambar = $this->upload->data("file_name");
                $data['gambar'] = $gambar;
            } else {
                $this->session->set_flashdata('message', ['message' => $this->upload->display_errors(), 'type' => 'danger']);
                redirect('menu');

            }
        }
        try {
            $this->db->insert('menus', $data);
            $this->session->set_flashdata('message', ['message' => 'Berhasil menambah menu', 'type' => 'success']);
            redirect('menu');
            
        } catch (\Throwable $th) {
            $this->session->set_flashdata('message', ['message' => 'Gagal menambah menu', 'type' => 'danger']);
            redirect('menu');
        }

    }

    function update($id){
        $post = $this->input->post();
        $data = $post;
        if(isset($_FILES['gambar']) && !empty($_FILES['gambar']) && !empty($_FILES['gambar']['name'])){
            $config["allowed_types"] = "jpg|png|jpeg";
            $config["upload_path"] = "./assets/img/products/";
            $config['file_name'] = random(8);
            $this->load->library("upload", $config);
            if ($this->upload->do_upload("gambar")) {
                $gambar = $this->upload->data("file_name");
                $data['gambar'] = $gambar;
                if(isset($post['old_gambar'])){
                    unlink('./assets/img/products/' . $post['old_gambar']);
                }
            } else {
                $this->session->set_flashdata('message', ['message' => $this->upload->display_errors(), 'type' => 'danger']);
                redirect('menu');

            }
        }
        if(isset($data['old_gambar'])) unset($data['old_gambar']);
        try {
            $this->db->where('id', $id)->update('menus', $data);
            $this->session->set_flashdata('message', ['message' => 'Berhasil mengupdate menu', 'type' => 'success']);
            redirect('menu');
            
        } catch (\Throwable $th) {
            $this->session->set_flashdata('message', ['message' => 'Gagal mengupdate menu', 'type' => 'danger']);
            redirect('menu');
        }
    }
    function delete($id){
        $this->db->where('id', $id)->delete('menus');
        $this->session->set_flashdata('message', ['message' => 'Berhasil menghapus menu', 'type' => 'success']);
        redirect('menu');
    }
}