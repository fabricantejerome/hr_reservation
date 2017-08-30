<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		// Load library
		$this->load->library('session');

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
		$this->_redirect_unauthorized();

		$data = array(
			'title'   => 'List of Rooms',
			'content' => 'rooms_view',
			'rooms'   => $this->rooms->browse()
		);

		$this->load->view('include/template', $data);
	}

	public function ajax_browse_rooms()
	{
		echo json_encode(array_column($this->rooms->ajax_browse(), 'room_name'));
	}

	public function room_form()
	{
		$this->_redirect_unauthorized();

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
		$this->_redirect_unauthorized();

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
		$this->_redirect_unauthorized();

		$id = $this->uri->segment(3) ? $this->uri->segment(3) : 0;

		$this->rooms->delete($id);

		$this->session->set_flashdata('success_message', '<span class="col-sm-12 alert alert-success">Room has been deleted!</span>');

		redirect('index.php/admin/rooms');
	}

	public function reservation_form()
	{
		$this->_redirect_unauthorized();

		$data = array(
				'title'   => 'File Reservation',
				'content' => 'room_reservation_form_view',
				'rooms'   => $this->rooms->browse(0),
				'agree'   => $this->rooms->exist_agreement(),
				'users'   => $this->ipc->fetch_resource()
			);

		$this->load->view('/include/template', $data);
	}

	public function reservation_submit()
	{
		$current_date  = date('Y/m/d H:i:s');
		$date_reserved = date('Y/m/d', strtotime($this->input->post('date_reserved')));
		$room_id       = $this->input->post('room_id');
		$time_start    = date('H:i:s', strtotime($this->input->post('time_start')));
		$time_end      = date('H:i:s', strtotime($this->input->post('time_end')));

		$params = array(
				'room_ids'      => $this->_get_associated_ids($room_id),
				'date_reserved' => $date_reserved
			);

		$config = array(
				'room_id'       => $room_id,
				'user_id'       => empty($this->input->post('emp_id')) ? $this->session->userdata('id') : $this->input->post('emp_id'),
				'purpose'       => $this->input->post('purpose'),
				'date_reserved' => $date_reserved,
				'time_start'    => $time_start,
				'time_end'      => $time_end,
				'date_filed'    => $current_date

			);

		if ($this->_is_available($params, $time_start, $time_end))
		{
			$id      = $this->rooms->store_reservation($config);
			$item    = $this->rooms->read_pending_request($id);
			$user_id = $this->session->userdata('id');

			$config = array(
					'room_res_id'       => $item['id'],
					'approved_datetime' => $current_date,
					'user_id'           => $user_id
				);

			$this->rooms->store_agreement();
			$this->rooms->store_approved_request($config);

			$this->session->set_flashdata('success_message', '<span class="col-sm-12 alert alert-success">Reservation has been filed!</span>');
		}
		else 
		{
			$this->session->set_flashdata('error_message', '<span class="col-sm-12 alert alert-error">There was a conflict on your reservation!</span>');
		}

		redirect($this->agent->referrer());
	}

	public function display_pending_request()
	{
		$this->_redirect_unauthorized();

		$rooms = $this->rooms->get_pending_request();

		$config = array();

		foreach($rooms as $row)
		{
			$info = $this->ipc->fetch_personal_info(array('id' => $row['user_id']));

			$config[] = array(
					'id'            => $row['id'],
					'purpose'       => $row['purpose'],
					'date_reserved' => $row['date_reserved'],
					'user_id'       => $row['user_id'],
					'time_start'    => $row['time_start'],
					'time_end'      => $row['time_end'],
					'date_filed'    => $row['date_filed'],
					'room_no'       => $row['room_no'],
					'room_name'     => $row['room_name'],
					'fullname'      => $info['fullname'],
					'section'       => $info['section_abbrev']
				);
		}

		$data = array(
				'title'   => 'List of Pending Request',
				'content' => 'room_pending_request_view',
				'rooms'   => $config
			);

		$this->load->view('include/template', $data);
	}

	public function read_approval_link()
	{
		$id      = $this->uri->segment(3);
		$user_id = $this->uri->segment(4);
		$entity  = $this->rooms->read_pending_request($id) ? $this->rooms->read_pending_request($id) : '';

		if (is_array($entity))
		{
			$info    = $this->ipc->fetch_personal_info(array('id' => $entity['user_id']));
			$entity['fullname'] = $info['fullname'];
			$entity['section']  = $info['section_abbrev'];
		}

		$this->_grant_privilege($user_id);
		
		$data = array(
				'title'   => is_array($entity) ? 'Room Reservation Request' : 'Request has been approved',
				'content' => 'room_approval_link_view',
				'row'     => $entity
			);

		$this->load->view('include/template', $data);
	}

	protected function _grant_privilege($uid)
	{
		$this->load->library('session');

		$user = $this->ipc->fetch_personal_info(array('id' => $uid));
		$dept_head = $this->ipc->fetch_department_head($user['employee_no']);

		$config = array(
				'id'               => $user['id'],
				'employee_no'      => $user['employee_no'],
				'fullname'         => $user['fullname'],
				'section'          => $user['section'],
				'email'            => $user['requestor_email'],
				'supervisor_email' => $dept_head['supervisor_email'],
				'user_type'        => 'admin',
				'grant'            => true
			);

		$this->session->set_userdata($config);
	}

	protected function _remove_priviledge()
	{
		if ($this->session->userdata('grant'))
		{
			$this->session->sess_destroy();
		}
	}

	public function approved_request()
	{
		$this->_redirect_unauthorized();

		$room_res_id  = $this->uri->segment(3);
		$current_date = date('Y/m/d H:i:s');
		
		$pending = $this->rooms->read_pending_request($room_res_id);
		
		$params = array(
				'room_ids'      => $this->_get_associated_ids($pending['room_id']),
				'date_reserved' => $pending['date_reserved']
			);

		if ($this->_is_available($params, $pending['time_start'], $pending['time_end']))
		{
			$user_id      = $this->session->userdata('id');
			$subject      = 'Approved Reservation';

			$config = array(
					'room_res_id'       => $room_res_id,
					'approved_datetime' => $current_date,
					'user_id'           => $user_id
				);

			$this->rooms->store_approved_request($config);

			$item      = $this->rooms->read_approved_request($room_res_id);
			$user      = $this->ipc->fetch_personal_info(array('id' => $item['user_id']));
			$dept_head = $this->ipc->fetch_department_head($user['employee_no']);
			$approver  = $this->ipc->fetch_personal_info(array('id' => $item['approver_id']));

			$item['fullname']       = $user['fullname'];
			$item['section_abbrev'] = $user['section_abbrev'];
			$item['section']        = $user['section'];
			$item['subject']        = $subject;
			$item['approver']       = $approver['fullname'];

			$config = array(
						'subject'          => $subject,
						'item'             => $item,
						'email'            => $user['requestor_email'],
						'supervisor_email' => $dept_head['supervisor_email']
					);

			$this->send_mail($config);

			$this->session->set_flashdata('success_message', '<span class="col-sm-12 alert alert-success">Reservation has been approved!</span>');

			$this->_remove_priviledge();
		}
		else
		{
			$this->session->set_flashdata('error_message', '<span class="col-sm-12 alert alert-error">The time slot for this room has been taken!</span>');
		}

		redirect($this->agent->referrer());
	}

	// Return true if available
	protected function _is_available($params, $ts, $te)
	{
		$items = $this->rooms->fetch_possible_conflict($params);

		// Convert to standard format
		$params['date_reserved'] = date('Y-m-d', strtotime($params['date_reserved']));

		// Convert to standard format
		$new_datetime_start = DateTime::createFromFormat('Y-m-d H:i:s', $params['date_reserved'] . ' ' . $ts);
		$new_datetime_end   = DateTime::createFromFormat('Y-m-d H:i:s', $params['date_reserved'] . ' ' . $te);

		foreach ($items as $row) 
		{
			$datetime_start = DateTime::createFromFormat('Y-m-d H:i:s', $row->date_reserved . ' ' . $row->time_start);
			$datetime_end   = DateTime::createFromFormat('Y-m-d H:i:s', $row->date_reserved . ' ' . $row->time_end);

			if (($new_datetime_start >= $datetime_start && $new_datetime_start <= $datetime_end) 
				|| ($new_datetime_end >= $datetime_start && $new_datetime_end <= $datetime_end)
				|| ($datetime_start >= $new_datetime_start && $datetime_start <= $new_datetime_end)
				|| ($datetime_end >= $new_datetime_start && $datetime_end <= $new_datetime_end))
			{
				return false;
			}
		}

		return true;
	}

	// Get the room id of associated rooms
	protected function _get_associated_ids($rid)
	{
		$entity = $this->rooms->read($rid);

		$tags = explode(',', $entity['tags']);

		$room_ids = $this->rooms->fetch_id_by_tags($tags) ? array_column($this->rooms->fetch_id_by_tags($tags), 'id') : array();

		array_unshift($room_ids, $entity['id']);

		return $room_ids;
	}

	public function display_approved_request()
	{
		$this->_redirect_unauthorized();

		$requests = $this->rooms->get_approved_request();

		$config = array();

		foreach($requests as $row)
		{
			$approver  = $this->ipc->fetch_personal_info(array('id' => $row['approver_id']));
			$requestor = $this->ipc->fetch_personal_info(array('id' => $row['user_id']));

			$config[] = array(
					'id'                => $row['id'],
					'room_res_id'       => $row['room_res_id'],
					'approved_datetime' => $row['approved_datetime'],
					'approver'          => $approver['fullname'],
					'date_reserved'     => $row['date_reserved'],
					'purpose'           => $row['purpose'],
					'time_start'        => $row['time_start'],
					'time_end'          => $row['time_end'],
					'fullname'          => $requestor['fullname'],
					'section'           => $requestor['section_abbrev'],
					'room_name'         => $row['room_name']
				);
		}

		$data = array(
				'title'    => 'List of Approved Request',
				'content'  => 'room_approved_request_view',
				'requests' => $config
			);
		
		$this->load->view('include/template', $data);
	}

	public function ajax_approved_request()
	{
		$room_id = $this->uri->segment(3);
		$requests = array();

		if (is_null($room_id))
		{
			$requests = $this->rooms->get_approved_request();
		}
		else
		{
			$ids = $this->_get_associated_ids($room_id);

			$params['room_ids'] = $ids;

			$requests = $this->rooms->fetch_by_room_id($params);
		}

		$config = array();

		foreach($requests as $row)
		{
			$requestor = $this->ipc->fetch_personal_info(array('id' => $row['user_id']));

			$config[] = array(
					'title'           => $row['room_name'] . ' / ' . ucwords(strtolower($requestor['fullname'])) . ' / ' . $requestor['section_abbrev'],
					'start'           => date('D M d Y H:i:s', strtotime($row['date_reserved'] . ' ' . $row['time_start'])),
					'end'             => date('D M d Y H:i:s', strtotime($row['date_reserved'] . ' ' . $row['time_end'])),
					'backgroundColor' => '#77dd77',
					'borderColor'     => '#77dd77',
				);
		}

		echo json_encode($config);
	}

	public function display_disapproved_form()
	{
		$this->_redirect_unauthorized();

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
		$this->_redirect_unauthorized();

		$room_res_id  = $this->input->post('room_res_id');
		$current_date = date('Y/m/d H:i:s');
		$reason       = $this->input->post('reason');
		$user_id      = $this->session->userdata('id');
		$subject      = 'Denied Reservation';

		$config = array(
				'room_res_id'     => $room_res_id,
				'denied_datetime' => $current_date,
				'reason'          => $this->input->post('reason'),
				'user_id'         => $user_id

			);

		$this->rooms->store_disapproved_request($config);

		$item      = $this->rooms->read_disapproved_request($room_res_id);
		$user      = $this->ipc->fetch_personal_info(array('id' => $item['user_id']));
		$dept_head = $this->ipc->fetch_department_head($user['employee_no']);
		$approver  = $this->ipc->fetch_personal_info(array('id' => $item['approver_id']));

		$item['fullname']       = $user['fullname'];
		$item['section_abbrev'] = $user['section_abbrev'];
		$item['section']        = $user['section'];
		$item['subject']        = $subject;
		$item['approver']       = $approver['fullname'];

		$config = array(
					'subject'          => $subject,
					'item'             => $item,
					'header'           => 'Denied by',
					'email'            => $user['requestor_email'],
					'supervisor_email' => $dept_head['supervisor_email']
				);

		$this->send_mail($config);

		$this->session->set_flashdata('success_message', '<span class="col-sm-12 alert alert-success">Request has been disapproved!</span>');

		redirect(base_url('index.php/admin/display_pending_request'));
	}

	public function display_disapproved_request()
	{
		$this->_redirect_unauthorized();

		$requests = $this->rooms->get_disapproved_request();

		$config = array();

		foreach($requests as $row)
		{
			$approver  = $this->ipc->fetch_personal_info(array('id' => $row['approver_id']));
			$requestor = $this->ipc->fetch_personal_info(array('id' => $row['user_id']));

			$config[] = array(
					'id'              => $row['id'],
					'room_res_id'     => $row['room_res_id'],
					'denied_datetime' => $row['denied_datetime'],
					'approver'        => $approver['fullname'],
					'date_reserved'   => $row['date_reserved'],
					'purpose'         => $row['purpose'],
					'time_start'      => $row['time_start'],
					'time_end'        => $row['time_end'],
					'fullname'        => $requestor['fullname'],
					'section'         => $requestor['section_abbrev'],
					'room_name'       => $row['room_name'],
					'reason'          => $row['reason']
				);
		}

		$data = array(
				'title'    => 'List of Denied Request',
				'content'  => 'room_disapproved_request_view',
				'requests' => $config
			);

		$this->load->view('include/template', $data);
	}

	protected function _redirect_unauthorized()
	{
		if (count($this->session->userdata()) < 3)
		{
			$this->session->set_flashdata('message', '<span class="col-sm-12 alert alert-warning">You must Login first!</span>');
			
			redirect('http://172.16.1.34/ipc_central', 'refresh');
		}
	}

	public function cancel_request()
	{
		$this->_redirect_unauthorized();

		$room_res_id  = $this->uri->segment(3);
		$current_date = date('Y/m/d H:i:s');
		$user_id      = $this->session->userdata('id');
		$subject      = 'Cancelled Reservation';

		$config = array(
				'room_res_id'        => $room_res_id,
				'cancelled_datetime' => $current_date,
				'user_id'            => $user_id
			);

		$this->rooms->store_cancel_request($config);

		$item      = $this->rooms->read_cancelled_request($room_res_id);
		$user      = $this->ipc->fetch_personal_info(array('id' => $item['user_id']));
		$dept_head = $this->ipc->fetch_department_head($user['employee_no']);
		$approver  = $this->ipc->fetch_personal_info(array('id' => $item['approver_id']));

		$item['fullname']       = $user['fullname'];
		$item['section_abbrev'] = $user['section_abbrev'];
		$item['section']        = $user['section'];
		$item['subject']        = $subject;
		$item['approver']       = $approver['fullname'];

		$config = array(
					'subject'          => $subject,
					'item'             => $item,
					'header'           => 'Cancelled by',
					'email'            => $user['requestor_email'],
					'supervisor_email' => $dept_head['supervisor_email']
				);

		$this->send_mail($config);

		$this->session->set_flashdata('success_message', '<span class="col-sm-12 alert alert-success">Reservation has been cancelled!</span>');

		redirect($this->agent->referrer());
	}

	public function display_cancelled_request()
	{
		$this->_redirect_unauthorized();

		$requests = $this->rooms->get_cancelled_request();

		$config = array();

		foreach($requests as $row)
		{
			$approver  = $this->ipc->fetch_personal_info(array('id' => $row['approver_id']));
			$requestor = $this->ipc->fetch_personal_info(array('id' => $row['user_id']));

			$config[] = array(
					'id'                 => $row['id'],
					'room_res_id'        => $row['room_res_id'],
					'cancelled_datetime' => $row['cancelled_datetime'],
					'approver'           => $approver['fullname'],
					'date_reserved'      => $row['date_reserved'],
					'purpose'            => $row['purpose'],
					'time_start'         => $row['time_start'],
					'time_end'           => $row['time_end'],
					'fullname'           => $requestor['fullname'],
					'section'            => $requestor['section_abbrev'],
					'room_name'          => $row['room_name'],
					'reason'             => $row['reason']
				);
		}

		$data = array(
				'title'    => 'List of Cancelled Request',
				'content'  => 'room_cancelled_request_view',
				'requests' => $config
			);

		$this->load->view('include/template', $data);
	}

	public function send_mail($params)
	{
		$this->load->library('emailerphp');
		$this->load->helper('array_flatten');

		$mail = new EmailerPHP;

		$mail->Subject = $params['subject'];

		if ($this->session->userdata('user_type') == 'admin')
		{
			$mail->addAddress($params['email']);
			$mail->addCC($params['supervisor_email']);
			$mail->addCC('joyce-ramirez@isuzuphil.com');
			$mail->addCC('may-galolo@isuzuphil.com');
			$mail->addCC('jerome-fabricante@isuzuphil.com');
			$mail->addCC('daoni-carlos@isuzuphil.com');
		}
		else 
		{
			$mail->addAddress('joyce-ramirez@isuzuphil.com');
			$mail->addCC('may-galolo@isuzuphil.com');
			$mail->addCC('daoni-carlos@isuzuphil.com');
			$mail->addCC($this->session->userdata('email'));
			$mail->addCC($this->session->userdata('supervisor_email'));
			$mail->addCC('jerome-fabricante@isuzuphil.com');
		}
		
		$data['mail']   = $mail ? $mail : '';
		$data['item']   = $params['item'];
		$data['header'] = isset($params['header']) ? $params['header'] : 'Approved by';

		$mail->Body = $this->load->view('email/notification', $data, true);

		if(!$mail->send())
		{
		    echo 'Message could not be sent.';
		    echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
		else 
		{
		    echo 'Message has been sent';
		}
	}

	// Send a reminder
	public function send_reminder()
	{
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 7200);

		// Hardcoded uid for extracting credentials when sending reminder
		$this->_grant_privilege(733);

		$items        = $this->rooms->fetch_approved_request();
		$current_date = date('Y-m-d');

		foreach ($items as $item)
		{
			
			$date_reserved = $item['date_reserved'];
			$date_filed    = date('Y-m-d', strtotime($item['date_filed']));
			$user          = $this->ipc->fetch_personal_info(array('id' => $item['user_id']));
			$dept_head     = $this->ipc->fetch_department_head($user['employee_no']);
			$approver      = $this->ipc->fetch_personal_info(array('id' => $item['approver_id']));

			$item['fullname']       = $user['fullname'];
			$item['section_abbrev'] = $user['section_abbrev'];
			$item['section']        = $user['section'];
			$item['subject']        = 'Reminder';
			$item['approver']       = $approver['fullname'];

			if ($days >= 0 && $days <= 1) 
			{
				$config = array(
							'subject'          => 'Reminder',
							'item'             => $item,
							'email'            => $user['requestor_email'],
							'supervisor_email' => $dept_head['supervisor_email']
						);

				$this->send_mail($config);
				$this->_send_cancel_link($config);
			}
		}

		$this->_remove_priviledge();
	}

	protected function _send_cancel_link($params)
	{
		$this->load->library('emailerphp');
		$this->load->helper('array_flatten');

		$mail = new EmailerPHP;

		$mail->Subject = $params['subject'];
		$mail->addAddress($params['email']);

		$data['item']   = $params['item'];
		$data['link']   = base_url('index.php/requestor/read_cancellation_link/' . $params['item']['room_res_id'] . '/' . $params['item']['user_id']);
		$data['header'] = isset($params['header']) ? $params['header'] : 'Approved by';
		$data['mail']   = $mail ? $mail : '';

		$mail->Body = $this->load->view('email/notification', $data, true);

		if(!$mail->send())
		{
		    echo 'Message could not be sent.';
		    echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
		else
		{
		    echo 'Cancel link has been sent.';
		}
	}

	public function calendar()
	{
		$data = array(
				'title'   => 'Calendar Report',
				'content' => 'room_resevation_calendar_view',
				'rooms'   => $this->rooms->browse(0)
			);

		$this->load->view('include/template', $data);
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
