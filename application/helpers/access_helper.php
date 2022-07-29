<?php


function must_login()
{
	if (!is_login()) {
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