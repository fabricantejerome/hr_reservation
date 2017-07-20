<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Set the timezone
		date_default_timezone_set('Asia/Manila');

		// Load helpers
		$helpers = array('form');

		$this->load->helper($helpers);

		// Load Model
		$this->load->model('room_model', 'rooms');
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
		$this->rooms->store();

		redirect('index.php/admin/rooms');
	}

	public function room_delete()
	{
		$id = $this->uri->segment(3) ? $this->uri->segment(3) : 0;

		$this->rooms->delete($id);

		redirect('index.php/admin/rooms');
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

		redirect(base_url('index.php/admin/display_pending_request'));

	}

	public function display_approved_request()
	{
		$data = array(
				'title'   => 'List of Approved Request',
				'content' => 'room_approved_request_view',
				'requests'   => $this->rooms->get_approved_request(0)
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

		redirect(base_url('index.php/admin/display_pending_request'));
	}

	public function display_disapproved_request()
	{
		$data = array(
				'title'   => 'List of Denied Request',
				'content' => 'room_disapproved_request_view',
				'requests' => $this->rooms->get_disapproved_request(0)
			);

		$this->load->view('include/template', $data);
	}

}
