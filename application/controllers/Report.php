<?php

use Spipu\Html2Pdf\Tag\Html\Em;

class Report extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Dompdf_gen');
		$this->load->model('Report_model');
		$this->load->helper('html2pdf');
		must_login();
	}

	function data($jenis){
		if(!in_array($jenis, ['transaksi', 'barang'])){
			show_error("Tidak Ada Data ". ucfirst($jenis) . " Yang Bisa Di Export", 403, 'Ilegal Akses');
		}
		$this->load->model('Gudang_model');
		$this->load->view('reports/v_export', [
			'title' => 'Export Data ' . kapitalize($jenis),
			'jenis' => $jenis,
			'admin' => sessiondata('login', 'user_role') == 'admin',
			'listGudang' => $this->Gudang_model->getBy(null, false)

		]);
	}

	function rtransaksi($gudang = 'semua', $jenis = null, $tgl = null){
		$this->load->helper('html2pdf');
		$this->load->model("Gudang_model");
		$title = 'Laporan Transaksi';
		$filter = [];
		if($jenis == 'semua') $jenis = null;
		if(!empty($tgl)){
			$tgl = explode('_', $tgl);
			$filter[] = 'DATE(transaksi.transaksi_date) BETWEEN "' . $tgl[0] . '" AND "' . $tgl[1] .'"';
		}
		if(sessiondata('login', 'user_role') == 'staff') $filter[]= 'transaksi.dihapus is NULL';
		$data = [
			'gudang' => $this->Gudang_model->report('transaksi', $gudang, $jenis, $filter),
			'tgl' => $tgl,
			'sgudang' => $gudang

		];
		if(empty($jenis)) $jenis = 'semua';
		else $title .= " " . kapitalize($jenis);

		if(!empty($tgl)){
			$title .= $tgl[0] . ' - ' . $tgl[1];
		}

		$html = $this->load->view('reports/transactions/' . $jenis, $data, true);
		buat_pdf($html, $title);
	}
	function rbarang($sgudang = null, $tgl = null){
		$this->load->helper('html2pdf');
		$this->load->model("Gudang_model");
		$title = 'Laporan Barang';
		$filter = [];
		if(!empty($tgl)){
			$tgl = explode('_', $tgl);
			$filter[] = 'DATE(barang_gudang.ditambah) BETWEEN "' . $tgl[0] . '" AND "' . $tgl[1] . '"';
		}
		$data = [
			'gudang' => $this->Gudang_model->report('barang', $sgudang, null, $filter),
			'tgl' => $tgl,
			'sgudang' => $sgudang
		];
		// response($data['gudang']);
		if(!empty($tgl)){
			$title .= $tgl[0] . ' - ' . $tgl[1];
		}

		$html = $this->load->view('reports/barang', $data, true);
		buat_pdf($html, $title);
	}

}
