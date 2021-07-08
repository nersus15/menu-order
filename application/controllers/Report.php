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

	public function reportSuppliers()
	{
		$data["suppliers"] = $this->Report_model->getAllSuppliers();

		$this->load->view("reports/v_report_suppliers", $data);

		$paper_size = 'A4';
		$orientation = "portrait";
		$html = $this->output->get_output();
		$this->dompdf->set_paper($paper_size, $orientation);

		$this->dompdf->load_html($html);
		$this->dompdf->render();
		$this->dompdf->stream("REKAP_DATA_SUPPLIER.pdf", array('Attachment' => 0));
	}

	public function reportCustomers()
	{
		$data["customers"] = $this->Report_model->getAllCustomers();

		$this->load->view("reports/v_report_customers", $data);

		$paper_size = 'A4';
		$orientation = "portrait";
		$html = $this->output->get_output();
		$this->dompdf->set_paper($paper_size, $orientation);

		$this->dompdf->load_html($html);
		$this->dompdf->render();
		$this->dompdf->stream("REKAP_DATA_CUSTOMER.pdf", array('Attachment' => 0));
	}

	public function reportTransactions()
	{
		$data = [
			"title" => "Laporan Transaksi",
			"outcoming_year_options" => $this->Report_model->yearOptionsForOutcomingTransaction(),
			"incoming_year_options" => $this->Report_model->yearOptionsForIncomingTransaction(),
		];

		$this->load->view("reports/v_report_transactions", $data);
	}

	public function filterTransactionsReport()
	{
		if (!isset($_GET["reports"]) || empty($_GET["reports"]))
			die("Invalid");
		extract($_GET);

		$namaBulan = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
		if ($reports == '1') {
			if ($filter == '1') {
				$data = [
					"keterangan" => 'Data Transaksi Keluar Tanggal ' . date('d-m-y', strtotime($tanggal)),
					"url_cetak" => 'transaksikeluar/cetak?filter=1&tanggal=' . $tanggal,
					"transaksi_keluar" => $this->Report_model->viewOutcomingTransactionByDate($tanggal)
				];

				if (empty($data['transaksi_keluar'])) {
					$this->session->set_flashdata('message', ['message' => 'Data Tidak Ditemukan', 'type' => 'danger']);
					redirect("report/reporttransactions");
				}
				ob_start();
				$this->load->view("reports/transactions/outcoming_report.php", $data);
				$html = ob_get_clean();

				buat_pdf($html, 'LAPORAN_BARANG_KELUAR_HARIAN.pdf');
			} elseif ($filter == '2') {

				$data = [
					"keterangan" => 'Data Transaksi Keluar Bulan ' . $namaBulan[$bulan] . ' ' . $tahun,
					"url_cetak" => 'transaksikeluar/cetak?filter=2&bulan=' . $bulan . '&tahun=' . $tahun,
					"transaksi_keluar" => $this->Report_model->viewOutcomingTransactionByMonth($bulan, $tahun)
				];

				if (empty($data['transaksi_keluar'])) {
					$this->session->set_flashdata('message', ['message' => 'Data Tidak Ditemukan', 'type' => 'danger']);
					redirect("report/reporttransactions");
				}

				// cetak laporan
				ob_start();
				$this->load->view("reports/transactions/outcoming_report.php", $data);
				$html = ob_get_contents();
				ob_end_clean();

				buat_pdf($html, 'LAPORAN_BARANG_KELUAR_BULANAN.pdf');
			} elseif ($filter == '3') {
				$data = [
					"keterangan" => 'Data Transaksi Keluar Tahun ' . $tahun,
					"url_cetak" => 'transaksikeluar/cetak?filter=3&tahun=' . $tahun,
					"transaksi_keluar" => $this->Report_model->viewOutcomingTransactionByYear($tahun)
				];

				if (empty($data['transaksi_keluar'])) {
					$this->session->set_flashdata('message', ['message' => 'Data Tidak Ditemukan', 'type' => 'danger']);
					redirect("report/reporttransactions");
				}

				// cetak laporan
				ob_start();
				$this->load->view("reports/transactions/outcoming_report.php", $data);
				$html = ob_get_contents();
				ob_end_clean();
				buat_pdf($html, 'LAPORAN_BARANG_KELUAR_TAHUNAN.pdf');
			}
		} elseif ($reports == '2') {
			if ($filter == '1') {
				$data = [
					"keterangan" => 'Data Transaksi Masuk Tanggal ' . date('d-m-y', strtotime($tanggal)),
					"url_cetak" => 'transaksimasuk/cetak?filter=1&tanggal=' . $tanggal,
					"transaksi_masuk" => $this->Report_model->viewIncomingTransactionByDate($tanggal)
				];

				if (!$data["transaksi_masuk"]) {
					$this->session->set_flashdata('message', ['message' => 'Data Tidak Ditemukan', 'type' => 'danger']);
					redirect("report/reporttransactions");
				}

				// cetak laporan
				ob_start();
				$this->load->view("reports/transactions/incoming_report.php", $data);
				$html = ob_get_contents();
				ob_end_clean();

				buat_pdf($html, 'LAPORAN_BARANG_MASUK_HARIAN.pdf');
			} elseif ($filter == '2') {
				$data = [
					"keterangan" => 'Data Transaksi Masuk Bulan ' . $namaBulan[$bulan] . ' ' . $tahun,
					"url_cetak" => 'transaksimasuk/cetak?filter=2&bulan=' . $bulan . '&tahun=' . $tahun,
					"transaksi_masuk" => $this->Report_model->viewIncomingTransactionByMonth($bulan, $tahun)
				];

				if (empty($data['transaksi_masuk'])) {
					$this->session->set_flashdata('message', ['message' => 'Data Tidak Ditemukan', 'type' => 'danger']);
					redirect("report/reporttransactions");
				}

				// cetak laporan
				ob_start();
				$this->load->view("reports/transactions/incoming_report.php", $data);
				$html = ob_get_contents();
				ob_end_clean();
				buat_pdf($html, 'LAPORAN_BARANG_MASUK_BULANAN.pdf');
			} elseif ($filter == 3) {
				$data = [
					"keterangan" => 'Data Transaksi Masuk Tahun ' . $tahun,
					"url_cetak" => 'transaksimasuk/cetak?filter=3&tahun=' . $tahun,
					"transaksi_masuk" => $this->Report_model->viewIncomingTransactionByYear($tahun)
				];

				if (empty($data['transaksi_masuk'])) {
					$this->session->set_flashdata('message', ['message' => 'Data Tidak Ditemukan', 'type' => 'danger']);
					redirect("report/reporttransactions");
				}

				// cetak laporan
				ob_start();
				$this->load->view("reports/transactions/incoming_report.php", $data);
				$html = ob_get_contents();
				ob_end_clean();
				buat_pdf($html, 'LAPORAN_BARANG_MASUK_TAHUNAN.pdf');
			}
		}
	}

	function stok()
	{
		$this->load->model('Item_model');
		$filter = $_GET;
		$bulan = date('n');
		$tahun = date('Y');

		if(!empty($filter)){
			$bulan = $filter['b'];
			$tahun = $filter['t'];
		}
		$q = [
			['type' => 'and', 'q' => 'YEAR(items.created_at) = ' . $tahun],
			['type' => 'and', 'q' => 'MONTH(items.created_at) = ' . $bulan]
		];
		$created_at = $this->db->select("MAX(YEAR(created_at)) max, MIN(YEAR(created_at)) min")->get('items')->row_array();
		$items = $this->Item_model->getAllItems($q);
		$data = [
			'title' => "Rekap Stok Barang",
			'items' => $items,
			'created_at' => $created_at,
			'def_bulan' =>  $bulan, 
			'def_tahun' => $tahun, 
		];

		$this->load->view('reports/v_stokbarang', $data);
	}

	function cetakstok()
	{
		$this->load->model('Item_model');
		$filter = $_GET;
		$bulan = date('n');
		$tahun = date('Y');

		if(!empty($filter)){
			$bulan = $filter['b'];
			$tahun = $filter['t'];
		}
		$q = [
			['type' => 'and', 'q' => 'YEAR(items.created_at) = ' . $tahun],
			['type' => 'and', 'q' => 'MONTH(items.created_at) = ' . $bulan]
		];
		$items = $this->Item_model->getAllItems($q);
		ob_start();
		$this->load->view("reports/template_reportstok.php", ['items' => $items, "def_tahun" => $tahun, "def_bulan" => $bulan]);
		$html = ob_get_clean();

		buat_pdf($html, 'Laporan_Stok_Barang.pdf');
	}
}
