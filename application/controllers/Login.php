<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$helpers = array('form');

		$this->load->helper($helpers);
	}

	public function index()
	{
		$this->load->view('login_view');
	}

	public function authenticate()
	{
		return true;
	}

	public function redirect_user()
	{
		if ($this->authenticate()) {
			$data = array(
				'title' => 'Dashboard',
				'content' => 'dashboard_view'
			);
			$this->load->view('include/template', $data);
		}
	}

	public function logout()
	{
		$this->index();
	}
}
