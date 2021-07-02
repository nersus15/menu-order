<?php
    use Dompdf\Dompdf;
    require_once "./application/third_party/dompdf-1.0.2/autoload.inc.php";

    function buat_pdf($html, $title = null){
        $pdf = new Dompdf();

		$pdf->loadHtml($html);
		// (Opsional) Mengatur ukuran kertas dan orientasi kertas
		$pdf->setPaper('A4', 'potrait');
		// Menjadikan HTML sebagai PDF
		$pdf->render();
		// Output akan menghasilkan PDF ke Browser
		$pdf->stream($title);
	}