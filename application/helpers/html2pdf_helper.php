<?php
    use Dompdf\Dompdf;
    require_once "./application/third_party/dompdf-1.0.2/autoload.inc.php";

    function buat_pdf($html, $title = null, $return = false){
        $pdf = new Dompdf();

		$pdf->loadHtml($html);
		// (Opsional) Mengatur ukuran kertas dan orientasi kertas
		$pdf->setPaper('A4', 'potrait');
		$pdf->render();
		if($return){
			file_put_contents(ASSET_PATH . 'nota/' . $title . '.pdf', $pdf->output());
			return $title . '.pdf';
		}else{
			// Menjadikan HTML sebagai PDF
			// Output akan menghasilkan PDF ke Browser
			$pdf->stream($title);
		}
		
	}