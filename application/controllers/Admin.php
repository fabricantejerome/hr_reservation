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
}
