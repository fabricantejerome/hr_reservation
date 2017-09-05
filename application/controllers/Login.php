<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$helpers = array('form');

		$this->load->model('Ipc_model', 'ipc');

		$this->load->helper($helpers);
	}

	public function index()
	{
		$prev_session = $this->_handle_session();

		$this->load->library('session');

		$config = array(
				'id'          => isset($prev_session['employee_id']) ? $prev_session['employee_id'] : '',
				'employee_no' => isset($prev_session['employee_no']) ? $prev_session['employee_no'] : ''
			);

		$user        = $this->ipc->fetch_personal_info($config);
		$dept_head   = $this->ipc->fetch_department_head($config['employee_no']);
		$user_access = $this->ipc->fetch_user_access($user['id']);

		$config = array(
				'id'               => $user['id'],
				'employee_no'      => $user['employee_no'],
				'fullname'         => $prev_session['full_name'],
				'section'          => $prev_session['section'],
				'department'       => $prev_session['department'],
				'email'            => $user['requestor_email'],
				'supervisor_email' => $dept_head['supervisor_email'],
				'user_type'        => $user_access['user_type_id'] == 2 ? 'admin' : 'requestor',
				'grant'            => false

			);

		$this->session->set_userdata($config);

		if($this->session->userdata('user_type') == 'admin')
		{
			redirect(base_url('index.php/admin/rooms'));
		}

		redirect(base_url('index.php/requestor/rooms'));	
	}

	protected function _handle_session()
	{
		$this->load->library('native_session');

		$session = $this->native_session->get_session();

		$this->native_session->destroy();

		return isset($session) ? $session : '';
	}

	public function authenticate()
	{
		$user_data = $this->_user_exist();

		if ($this->_validate_input() && is_array($user_data))
		{
			$user_data['fullname'] = ucwords(strtolower($user_data['fullname']));
			$fullname              = explode(',', $user_data['fullname']);
			$user_data['fullname'] = ($fullname[0]);

			$this->session->set_userdata($user_data);

			if($this->session->userdata('user_type') == 'admin')
			{
				redirect(base_url('index.php/admin/rooms'));
			}

			redirect(base_url('index.php/requestor/rooms'));

		}

		$data['message'] = '<span class="col-sm-12 alert alert-warning">You have no rights to access this system.</span>';

		$this->load->view('login_view', $data);

	}

	public function dashboard()
	{
		$data = array(
			'title' => 'Dashboard',
			'content' => 'dashboard_view',
		);

		$this->load->view('include/template', $data);
	}

	public function logout()
	{
		$this->load->library('session');

		$this->session->sess_destroy();

		//redirect('index.php/login/index');
		redirect('http://172.16.1.34/ipc_central/index.php');
	}

	protected function _validate_input()
	{
		$this->load->library('form_validation');

		$config = array(
		        array(
		                'field' => 'username',
		                'label' => 'Username',
		                'rules' => 'required|trim',
		                'errors' => array(
		                	'required' => 'You must provide a %s.',
		                ),
		        ),
		        array(
		                'field' => 'password',
		                'label' => 'Password',
		                'rules' => 'required|trim',
		                'errors' => array(
		                        'required' => 'You must provide a %s.',
		                ),
		        ),
			);

		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() == false)
		{
			return false;
		}

		return true;
	}

	protected function _user_exist()
	{
		$this->load->model('user_model', 'user');

		return is_array($this->user->exist()) ? $this->user->exist() : false;
	}

	protected function _remove_priviledge()
	{
		if ($this->session->userdata('grant'))
		{
			$this->session->sess_destroy();
		}
	}
}
