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
	if (!is_login('Manager')) {
		redirect('auth/block');		
	}
}