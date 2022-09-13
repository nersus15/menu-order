<?php

use Spipu\Html2Pdf\Tag\Html\Em;

class Report extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Dompdf_gen');
		// $this->load->model('Report_model');
		$this->load->helper('html2pdf');
		must_login();
	}

	function data($jenis){
		if(!in_array($jenis, ['transaksi', 'barang'])){
			show_error("Tidak Ada Data ". ucfirst($jenis) . " Yang Bisa Di Export", 403, 'Ilegal Akses');
		}
		$this->load->view('reports/v_export', [
			'title' => 'Export Data Penjualan',
			'jenis' => $jenis,

		]);
	}

	function rtransaksi($tgl = null){
		$this->load->helper('html2pdf');
		$this->load->model("Order_model");
		$title = 'Laporan Penjualan';
		$filter = ['pesanan.status' => 'CLOSE'];
		if(!empty($tgl)){
			$tgl = explode('_', $tgl);
			$title .= ' ' . $tgl[0] . ' sd ' . $tgl[1];
			$filter[] = 'DATE(pesanan.tanggal) BETWEEN "' . $tgl[0] . '" AND "' . $tgl[1] .'"';
		}
		$data = [
			'orders' => $this->Order_model->report($filter),
			'tgl' => $tgl,
		];
		// response($data['orders']);

		$html = $this->load->view('reports/transactions/index', $data, true);
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
