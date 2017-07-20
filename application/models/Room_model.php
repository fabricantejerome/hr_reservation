<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function browse()
	{
		$query = $this->db->get('room_tbl');

		return $query->result();
	}

	public function read($id)
	{
		$query = $this->db->get_where('room_tbl', array('id' => $id));

		return $query->row_array();
	}

	public function store()
	{
		$data = array(
				'room_no'     => $this->input->post('room_no'),
				'room_name'   => $this->input->post('room_name'),
				'capacity'    => $this->input->post('capacity'),
				'description' => $this->input->post('description')
			);

		$id = $this->input->post('id');

		if ( $id == 0) {
			$this->db->insert('room_tbl', $data);
		}
		else {

			$this->db->update('room_tbl', $data, array('id' => $id));
		}

		return $this;
		//var_dump($this->db->last_query());
	}

	public function delete($id)
	{
		$this->db->delete('room_tbl', array('id' => $id));

		return $this;
		//var_dump($this->db->last_query());
	}

	public function store_reservation($params)
	{
		$this->db->insert('room_res_tbl', $params);

		//var_dump($this->db->last_query());
	}

	public function get_taken_slot($params)
	{
		$query = $this->db->select('*')
				->from('room_res_tbl AS a')
				->join('users_tbl as b', 'a.user_id = b.id', 'INNER')
				->where($params)
				->get();
		
		return $query->result();
		//var_dump($this->db->last_query()); die;
	}

	public function get_pending_request()
	{
		$query = $this->db->select('a.id, a.purpose, a.date_reserved, a.time_start, a.time_end, a.date_filed, c.fullname, d.room_no')
				->from('room_res_tbl AS a')
				->join('approved_res_tbl AS b', 'a.id = b.room_res_id', 'LEFT')
				->join('users_tbl AS c', 'a.user_id = c.id', 'INNER')
				->join('room_tbl AS d', 'a.room_id = d.id',  'INNER')
				->where('b.room_res_id IS NULL')
				->get();

		return $query->result();
	}

	public function store_approved_request($params)
	{
		$this->db->insert('approved_res_tbl', $params);

		redirect(base_url('index.php/admin/get_pending_request'));
		//var_dump($this->db->last_query()); die;
	}

}