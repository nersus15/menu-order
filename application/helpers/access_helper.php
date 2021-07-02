<?php


function must_login()
{
	$mustLogin = get_instance();
	if (!$mustLogin->session->userdata('user_email')) {
		redirect('auth');
	}
}

function must_admin()
{
	$mustAdmin = get_instance();
	if ($mustAdmin->session->userdata("user_role") !== "admin") {
		redirect('auth/block');		
	}
}
function rupiah_format($angka)
{
    $hasil_rupiah = "Rp. " . number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
}