<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requestor extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->_redirect_unauthorized();

		// Set the timezone
		date_default_timezone_set('Asia/Manila');

		// Load helpers
		$helpers = array('form');

		$this->load->helper($helpers);

		$this->load->model('room_model', 'rooms');
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
			$id = $this->rooms->store_reservation($config);

			$config = array(
					'subject' => $this->input->post('id') ? 'Update Reservation Details' : 'Filed Reservation',
					'item'    => $this->rooms->read_pending_request($id)
				);

			$this->send_mail($config);

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
				|| ($new_datetime_end >= $datetime_start && $new_datetime_end <= $datetime_end))
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
				'date_reserved >=' => $current_date,
			);

		echo $this->rooms->get_taken_slot($config) ? json_encode($this->rooms->get_taken_slot($config)) : '';
	}

	public function display_pending_request()
	{
		$user_id = $this->session->userdata('id');

		$data = array(
				'title'   => 'List of Pending Request',
				'content' => 'room_pending_request_view',
				'rooms'   => $this->rooms->get_pending_request($user_id)
			);

		$this->load->view('include/template', $data);
	}

	public function display_approved_request()
	{
		$user_id = $this->session->userdata('id');

		$data = array(
				'title'    => 'List of Approved Request',
				'content'  => 'room_approved_request_view',
				'requests' => $this->rooms->get_approved_request($user_id)
			);
		
		$this->load->view('include/template', $data);
	}

	public function display_disapproved_request()
	{
		$user_id = $this->session->userdata('id');

		$data = array(
				'title'    => 'List of Denied Request',
				'content'  => 'room_disapproved_request_view',
				'requests' => $this->rooms->get_disapproved_request($user_id)
			);

		$this->load->view('include/template', $data);
	}

	public function display_cancelled_request()
	{
		$user_id = $this->session->userdata('id');

		$data = array(
				'title'    => 'List of Cancelled Request',
				'content'  => 'room_cancelled_request_view',
				'requests' => $this->rooms->get_cancelled_request($user_id)
			);

		$this->load->view('include/template', $data);
	}

	protected function _redirect_unauthorized()
	{
		if (count($this->session->userdata()) < 2 && $this->session->userdata('user_type') == 'requestor')
		{
			$this->session->set_flashdata('message', '<span class="col-sm-12 alert alert-warning">You must Login first!</span>');
			
			redirect(base_url('index.php/login/index'));
		}
	}

	public function send_mail($params)
	{
		$this->load->library('emailerphp');

		$mail = new EmailerPHP;

		$mail->Subject = $params['subject'];
		$mail->addAddress('jerome-fabricante@isuzuphil.com');
		$mail->addCC('fabricantejerome@gmail.com');

		$data['item']   = $params['item'];
		$data['header'] = isset($params['header']) ? $params['header'] : 'Approved by';

		$mail->Body = $this->load->view('email/notification', $data, true);

		if(!$mail->send()) {
		    echo 'Message could not be sent.';
		    echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
		    echo 'Message has been sent';
		}
	}

}
