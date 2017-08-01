<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Native_session {

	public function __construct()
	{
		session_start();
	}

	public function get_session()
	{
		return isset($_SESSION['user_data']) ? $_SESSION['user_data'] : '';
	}

	public function destroy()
	{
		session_destroy();
	}
}