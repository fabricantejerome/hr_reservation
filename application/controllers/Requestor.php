<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requestor extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
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

	public function reservation_form()
	{
		$data = array(
				'title' => 'Reservation Form',
				'content' => 'room_reservation_form_view',
				'rooms' => $this->rooms->browse()
			);

		$this->load->view('/include/template', $data);
	}

	public function reservation_submit()
	{

		$current_date = date('Y/m/d H:i:s');
		$date_reserved = date('Y/m/d', strtotime($this->input->post('date_reserved')));

		$config = array(
				'room_id'       => $this->input->post('room_id'),
				'user_id'       => $this->session->userdata('id'),
				'purpose'       => $this->input->post('purpose'),
				'date_reserved' => $date_reserved,
				'time_start'    => $this->input->post('time_start'),
				'time_end'      => $this->input->post('time_end'),
				'date_filed'    => $current_date

			);

		$this->rooms->store_reservation($config);
		//var_dump($this->input->post());
	}

	public function show_room_reserved()
	{
		$id = $this->uri->segment(3);

		$current_date = date('Y/m/d');

		$config = array(
				'room_id'          => $id,
				'date_reserved >=' => $current_date,
			);

		echo $this->rooms->get_taken_slot($config)? json_encode($this->rooms->get_taken_slot($config)) : '';
	}


	protected function _validate_reservation_input()
	{
		/*$this->load->library('form_validation');

		$config = array(
		        array(
		                'field' => 'date_reserved',
		                'label' => 'Date',
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
			);*/

	}

}
