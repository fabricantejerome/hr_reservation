<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requestor extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		// Load library
		$this->load->library('session');

		$this->_redirect_unauthorized();

		// Set the timezone
		date_default_timezone_set('Asia/Manila');

		// Load helpers
		$helpers = array('form');

		$this->load->helper($helpers);

		$this->load->model('room_model', 'rooms');
		$this->load->model('ipc_model', 'ipc');
	}

	public function dashboard()
	{
		$data = array(
			'title'   => 'Requestor Dashboard',
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

	public function reservation_form()
	{
		$id = $this->uri->segment(3) ? $this->uri->segment(3) : '';

		$data = array(
				'title'   => $id ? 'Update Details' : 'File Reservation',
				'content' => 'room_reservation_form_view',
				'rooms'   => $this->rooms->browse(0),
				'item'    => $id ? $this->rooms->read_pending_request($id) : ''
			);

		$this->load->view('/include/template', $data);
	}

	public function reservation_submit()
	{
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 3600);

		$current_date  = date('Y/m/d H:i:s');
		$date_reserved = date('Y/m/d', strtotime($this->input->post('date_reserved')));
		$room_id       = $this->input->post('room_id');
		$time_start    = date('H:i:s', strtotime($this->input->post('time_start')));
		$time_end      = date('H:i:s', strtotime($this->input->post('time_end')));

		$params = array(
				'room_id'       => $room_id,
				'date_reserved' => $date_reserved
			);

		$config = array(
				'room_id'       => $room_id,
				'user_id'       => $this->session->userdata('id'),
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
			$subject = $this->input->post('id') ? 'Update Reservation Details' : 'Request Room Reservation';
			$user    = $this->ipc->fetch_personal_info(array('id' => $this->session->userdata('id')));

			$item['fullname']       = $user['fullname'];
			$item['section_abbrev'] = $user['section_abbrev'];
			$item['section']        = $user['section'];
			$item['subject']        = $subject;

			$config = array(
					'subject' => $subject,
					'item'    => $item
				);

			$this->send_mail($config);

			$this->_send_approval_link($config);

			if ($this->input->post('id') > 0)
			{
				$this->session->set_flashdata('success_message', '<span class="col-sm-12 alert alert-success">Reservation has been updated!</span>');
			}
			else
			{
				$this->session->set_flashdata('success_message', '<span class="col-sm-12 alert alert-success">Reservation has been filed!</span>');
			}
			
		}
		else 
		{
			$this->session->set_flashdata('error_message', '<span class="col-sm-12 alert alert-error">There was a conflict on your reservation!</span>');
		}

		redirect(base_url('index.php/requestor/reservation_form'));
	}

	protected function _send_approval_link($params)
	{
		$this->load->library('emailerphp');

		$mail = new EmailerPHP;

		$admin = $this->ipc->fetch_admin_users();

		foreach ($admin as $user)
		{
			$mail->Subject = $params['subject'];
			$mail->addAddress('jerome-fabricante@isuzuphil.com');

			$data['item']   = $params['item'];
			$data['link']	= base_url('index.php/admin/read_approval_link/' . $params['item']['id'] . '/' . $user->employee_id);
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
	}

	// Return true if available
	protected function _is_available($params, $ts, $te)
	{
		$items = $this->rooms->get_possible_conflict($params);

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

	public function show_room_reserved()
	{
		$id = $this->uri->segment(3);

		$current_date = date('Y/m/d');

		$config = array(
				'room_id'          => $id,
				'date_reserved >=' => $current_date
			);

		$items = $this->rooms->get_taken_slot($config);

		$config = array();

		foreach ($items as $row) {

			$user	  = $this->ipc->fetch_personal_info(array('id' => $row->user_id));
			$approver = $this->ipc->fetch_personal_info(array('id' => $row->approver_id));

			$config[] = array(
					'id'                => $row->id,
					'room_res_id'       => $row->room_res_id,
					'approved_datetime' => $row->approved_datetime,
					'approver_id'       => $row->approver_id,
					'date_reserved'     => $row->date_reserved,
					'purpose'           => $row->purpose,
					'time_start'        => $row->time_start,
					'time_end'          => $row->time_end,
					'user_id'           => $row->user_id,
					'room_name'         => $row->room_name,
					'fullname'          => $user['fullname'],
					'approver'          => $approver['fullname']
				);
		}

		echo $config ? json_encode($config) : '';
	}

	public function ajax_room_details()
	{
		$id = $this->uri->segment(3);

		echo $this->rooms->read($id) ? json_encode($this->rooms->read($id)) : '';
	}

	public function ajax_block_time()
	{
		$id   = $this->uri->segment(3);
		$date = date('Y-m-d', strtotime($this->uri->segment(4)));

		$config = array(
				'room_id'       => $id, 
				'date_reserved' => $date
			);

		$data = $this->rooms->fetch_block_time($config);

		$time_start = array_column($data, 'time_start');
		$time_end   = array_column($data, 'time_end');

		$col_start = array();

		foreach($time_start as $time)
		{
			$col_start[] = array(
					$time, substr_replace($time, '1', 7)
				);
		}

		$col_end = array();

		foreach ($time_end as $time) {
			$col_end[] = array(
					$time, substr_replace($time, '1', 7)
				);
		}

		$config = array($col_start, $col_end);

		echo json_encode($config);
	}


	public function display_pending_request()
	{
		$user_id = $this->session->userdata('id');
		$rooms   = $this->rooms->get_pending_request($user_id);

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

	public function display_approved_request()
	{
		$user_id = $this->session->userdata('id');

		$requests = $this->rooms->get_approved_request($user_id);

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

	public function display_disapproved_request()
	{
		$user_id = $this->session->userdata('id');

		$requests = $this->rooms->get_disapproved_request($user_id);

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

	public function display_cancelled_request()
	{
		$user_id = $this->session->userdata('id');

		$requests = $this->rooms->get_cancelled_request($user_id);

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

	protected function _redirect_unauthorized()
	{
		if (count($this->session->userdata()) < 3)
		{
			$this->session->set_flashdata('message', '<span class="col-sm-12 alert alert-warning">You must Login first!</span>');
			
			redirect('http://172.16.1.34/ipc_central', 'refresh');
		}
	}

	public function send_mail($params)
	{
		$this->load->library('emailerphp');
		$this->load->helper('array_flatten');

		$mail = new EmailerPHP;

		$mail->Subject = $params['subject'];
		/*$mail->addAddress('jerome-fabricante@isuzuphil.com');
		$mail->addCC('fabricantejerome@gmail.com');*/

		if ($this->session->userdata('user_type') == 'admin')
		{
			$mail->addAddress($params['email']);
			$mail->addCC($params['supervisor_email']);
			$mail->addCC('joyce-ramirez@isuzuphil.com');
			$mail->addCC('may-galolo@isuzuphil.com');
			$mail->addCC('daoni-carlos@isuzuphil.com');
			$mail->addCC('jerome-fabricante@isuzuphil.com');

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

		$data['mail']   = $mail;
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
}
