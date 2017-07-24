<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->_redirect_unauthorized();

		// Set the timezone
		date_default_timezone_set('Asia/Manila');

		// Load helpers
		$helpers = array('form');

		$this->load->helper($helpers);

		// Load Model
		$this->load->model('room_model', 'rooms');

		$this->load->model('ipc_model', 'ipc');

		$this->load->model('user_model', 'user');
	}

	public function dashboard()
	{
		$data = array(
			'title'   => 'Administrator Dashboard',
			'content' => 'dashboard_view'
		);

		$this->load->view('include/template', $data);
	}

	public function rooms() 
	{
		$data = array(
			'title'   => 'List of Rooms',
			'content' => 'rooms_view',
			'rooms'   => $this->rooms->browse()
		);

		$this->load->view('include/template', $data);
	}

	public function room_form()
	{
		$id = $this->uri->segment(3) ? $this->uri->segment(3) : 0;

		$data = array(
				'title'   => $id ? 'Edit Room Details' : 'Add Room',
				'content' => 'room_form_view',
				'room'    => $id ? $this->rooms->read($id) : 0
			);

		$this->load->view('include/template', $data);
	}

	public function room_store()
	{
		$id = $this->input->post('id');

		if ($id) {
			$this->session->set_flashdata('success_message', '<span class="col-sm-12 alert alert-success">Room has been updated!</span>');
		}
		else {
			$this->session->set_flashdata('success_message', '<span class="col-sm-12 alert alert-success">Room has been added!</span>');
		}

		$this->rooms->store();

		redirect('index.php/admin/rooms');
	}

	public function room_delete()
	{
		$id = $this->uri->segment(3) ? $this->uri->segment(3) : 0;

		$this->rooms->delete($id);

		$this->session->set_flashdata('success_message', '<span class="col-sm-12 alert alert-success">Room has been deleted!</span>');

		redirect('index.php/admin/rooms');
	}

	public function reservation_form()
	{
		$data = array(
				'title'   => 'File Reservation',
				'content' => 'room_reservation_form_view',
				'rooms'   => $this->rooms->browse()
			);

		$this->load->view('/include/template', $data);
	}

	public function display_pending_request()
	{

		$data = array(
				'title'   => 'List of Pending Request',
				'content' => 'room_pending_request_view',
				'rooms'   => $this->rooms->get_pending_request()
			);

		$this->load->view('include/template', $data);
	}

	public function approved_request()
	{
		$room_res_id  = $this->uri->segment(3);
		$current_date = date('Y/m/d H:i:s');
		$user_id      = $this->session->userdata('id');

		$config = array(
				'room_res_id'       => $room_res_id,
				'approved_datetime' => $current_date,
				'user_id'           => $user_id
			);

		$this->rooms->store_approved_request($config);

		$item = $this->rooms->read_approved_request($room_res_id);

		$config = array(
					'subject'          => 'Approved Reservation',
					'item'             => $item,
					'email'            => $item['email'],
					'supervisor_email' => $item['supervisor_email']
				);

		$this->send_mail($config);

		$this->session->set_flashdata('success_message', '<span class="col-sm-12 alert alert-success">Reservation has been approved!</span>');

		redirect(base_url('index.php/admin/display_pending_request'));

	}

	public function display_approved_request()
	{
		$data = array(
				'title'    => 'List of Approved Request',
				'content'  => 'room_approved_request_view',
				'requests' => $this->rooms->get_approved_request()
			);
		
		$this->load->view('include/template', $data);
	}

	public function display_disapproved_form()
	{
		$room_res_id = $this->uri->segment(3);

		$data = array(
				'title'       => 'Disapproved Request',
				'content'     => 'room_disapproved_form_view',
				'room_res_id' => $room_res_id
			);

		$this->load->view('include/template', $data);
	}

	public function disapproved_request()
	{
		$room_res_id  = $this->input->post('room_res_id');
		$current_date = date('Y/m/d H:i:s');
		$reason       = $this->input->post('reason');
		$user_id      = $this->session->userdata('id');

		$config = array(
				'room_res_id'     => $room_res_id,
				'denied_datetime' => $current_date,
				'reason'          => $this->input->post('reason'),
				'user_id'         => $user_id

			);

		$this->rooms->store_disapproved_request($config);

		$item = $this->rooms->read_disapproved_request($room_res_id);

		$config = array(
					'subject'          => 'Denied Reservation',
					'item'             => $item,
					'header'           => 'Denied by',
					'email'            => $item['email'],
					'supervisor_email' => $item['supervisor_email']
				);

		$this->send_mail($config);

		$this->session->set_flashdata('success_message', '<span class="col-sm-12 alert alert-success">Request has been disapproved!</span>');

		redirect(base_url('index.php/admin/display_pending_request'));
	}

	public function display_disapproved_request()
	{
		$data = array(
				'title'    => 'List of Denied Request',
				'content'  => 'room_disapproved_request_view',
				'requests' => $this->rooms->get_disapproved_request()
			);

		$this->load->view('include/template', $data);
	}

	protected function _redirect_unauthorized()
	{
		if (count($this->session->userdata()) < 2 && $this->session->userdata('user_type') == 'admin')
		{
			$this->session->set_flashdata('message', '<span class="col-sm-12 alert alert-warning">You must Login first!</span>');
			
			redirect(base_url('index.php/login/index'));
		}
	}

	public function cancel_request()
	{
		$room_res_id  = $this->uri->segment(3);
		$current_date = date('Y/m/d H:i:s');
		$user_id      = $this->session->userdata('id');

		$config = array(
				'room_res_id'        => $room_res_id,
				'cancelled_datetime' => $current_date,
				'user_id'            => $user_id
			);

		$this->rooms->store_cancel_request($config);

		$item = $this->rooms->read_cancelled_request($room_res_id);

		$config = array(
					'subject'          => 'Cancelled Reservation',
					'item'             => $item,
					'header'           => 'Cancelled by',
					'email'            => $item['email'],
					'supervisor_email' => $item['supervisor_email']
				);

		$this->send_mail($config);

		$this->session->set_flashdata('success_message', '<span class="col-sm-12 alert alert-success">Reservation has been cancelled!</span>');

		redirect($this->agent->referrer());
	}

	public function display_cancelled_request()
	{
		$data = array(
				'title'    => 'List of Cancelled Request',
				'content'  => 'room_cancelled_request_view',
				'requests' => $this->rooms->get_cancelled_request()
			);

		$this->load->view('include/template', $data);
	}

	public function send_mail($params)
	{
		$this->load->library('emailerphp');

		$mail = new EmailerPHP;

		$mail->Subject = $params['subject'];
		$mail->addAddress('jerome-fabricante@isuzuphil.com');
		$mail->addCC('fabricantejerome@gmail.com');

		$data['item']   = $params['item'];
		$data['header'] = $params['header'];
		$data['header'] = isset($params['header']) ? $params['header'] : 'Approved by';

		$mail->Body = $this->load->view('email/notification', $data, true);

		if(!$mail->send()) {
		    echo 'Message could not be sent.';
		    echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
		    echo 'Message has been sent';
		}
	}

	// Send a reminder
	public function send_reminder()
	{
		$items        = $this->rooms->fetch_approved_request();
		$current_date = date('Y-m-d');

		foreach ($items as $item) {
			$date_reserved = $item['date_reserved'];

			$days = $this->_date_diff($current_date, $date_reserved);

			if ($days >= 0 && $days <= 1) 
			{
				$config = array(
							'subject'          => 'Reminder',
							'item'             => $item,
							'email'            => $item['email'],
							'supervisor_email' => $item['supervisor_email']
						);

				$this->send_mail($config);
			}
		}
	}

	// Calculate date difference
	protected function _date_diff($s, $e)
	{
		$start = strtotime($s);
		$end   = strtotime($e);

		$days = ceil(($end - $start) / 86400);

		return $days;
	}

	public function user_form()
	{
		$data = array(
			'title'   => 'Create Account',
			'content' => 'user_form_view',
			'roles'   => $this->user->fetch_roles()
		);

		$this->load->view('include/template', $data);
	}

	public function store_user()
	{
		$username         = $this->input->post('username');
		$password         = $this->input->post('password');
		$fullname         = $this->input->post('fullname');
		$email            = $this->input->post('email');
		$emp_id           = $this->input->post('emp_id');
		$emp_no           = $this->input->post('emp_no');
		$supervisor_email = $this->input->post('supervisor_email');
		$role_id          = $this->input->post('role_id');

		$config = array(
				'username'         => $username,
				'password'         => $password,
				'fullname'         => $fullname,
				'email'            => $email,
				'emp_id'           => $emp_id,
				'emp_no'           => $emp_no,
				'supervisor_email' => $supervisor_email
			);

		$user_id = $this->user->store($config);

		$config = array(
				'user_id' => $user_id,
				'role_id' => $role_id
			);

		$this->user->assign_role($config);

		$this->session->set_flashdata('message', '<div class="alert alert-success">Account has been created!</div>');

		redirect($this->agent->referrer());
	}

	public function users()
	{

		$data = array(
				'content' => 'users_view',
				'title'   => 'List of Users',
				'users'   => $this->user->fetch()
			);

		$this->load->view('include/template', $data);
	}

}
