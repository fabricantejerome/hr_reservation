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

	public function get_possible_conflict($params)
	{
		$fields = array(
				'date_reserved',
				'time_start',
				'time_end'
			);

		$query = $this->db->select($fields)
				->from('approved_res_tbl AS a')
				->join('room_res_tbl AS b', 'a.room_res_id = b.id', 'INNER')
				->where($params)
				->get();

		return $query->result();
	}

	public function get_taken_slot($params)
	{
		$fields = array(
				'a.id',
				'a.room_res_id',
				'a.approved_datetime',
				'b.date_reserved',
				'b.purpose',
				'b.time_start',
				'b.time_end',
				'c.fullname',
				'd.room_no',
				'e.fullname AS approver'
			);

		$query = $this->db->select($fields)
				->from('approved_res_tbl AS a')
				->join('room_res_tbl AS b', 'a.room_res_id = b.id', 'INNER')
				->join('users_tbl AS c', 'b.user_id = c.id', 'INNER')
				->join('room_tbl AS d', 'b.room_id = d.id', 'INNER')
				->join('users_tbl AS e', 'a.user_id = e.id', 'INNER')
				->where($params)
				->get();

		return $query->result();

		//var_dump($this->db->last_query()); die;
	}

	public function get_pending_request($id = 0)
	{
		$fields = array(
				'a.id',
				'a.purpose',
				'a.date_reserved',
				'a.time_start',
				'a.time_end',
				'a.date_filed',
				'c.fullname',
				'd.room_no'
			);

		if ($id == 0)
		{
			$query = $this->db->select($fields)
					->from('room_res_tbl AS a')
					->join('approved_res_tbl AS b', 'a.id = b.room_res_id', 'LEFT')
					->join('users_tbl AS c', 'a.user_id = c.id', 'INNER')
					->join('room_tbl AS d', 'a.room_id = d.id',  'INNER')
					->join('disapproved_res_tbl AS e', 'a.id = e.room_res_id', 'LEFT')
					->join('cancelled_res_tbl AS f', 'a.id = f.room_res_id', 'LEFT')
					->where('b.room_res_id IS NULL')
					->where('e.room_res_id IS NULL')
					->where('f.room_res_id IS NULL')
					->get();
		}
		else
		{
			$clause = array(
					'a.user_id' => $id
				);

			$query = $this->db->select($fields)
					->from('room_res_tbl AS a')
					->join('approved_res_tbl AS b', 'a.id = b.room_res_id', 'LEFT')
					->join('users_tbl AS c', 'a.user_id = c.id', 'INNER')
					->join('room_tbl AS d', 'a.room_id = d.id',  'INNER')
					->join('disapproved_res_tbl AS e', 'a.id = e.room_res_id', 'LEFT')
					->join('cancelled_res_tbl AS f', 'a.id = f.room_res_id', 'LEFT')
					->where('b.room_res_id IS NULL')
					->where('e.room_res_id IS NULL')
					->where('f.room_res_id IS NULL')
					->where($clause)
					->get();
		}
		
		return $query->result();
	}

	public function store_approved_request($params)
	{
		$this->db->insert('approved_res_tbl', $params);
		//var_dump($this->db->last_query()); die;
	}

	public function get_approved_request($id = 0)
	{
		
		$fields = array(
				'a.id',
				'a.room_res_id',
				'a.approved_datetime',
				'b.date_reserved',
				'b.purpose',
				'b.time_start',
				'b.time_end',
				'c.fullname',
				'd.room_no',
				'e.fullname AS approver'
			);

		if ($id == 0)
		{
			$query = $this->db->select($fields)
					->from('approved_res_tbl AS a')
					->join('room_res_tbl AS b', 'a.room_res_id = b.id', 'INNER')
					->join('users_tbl AS c', 'b.user_id = c.id', 'INNER')
					->join('room_tbl AS d', 'b.room_id = d.id', 'INNER')
					->join('users_tbl AS e', 'a.user_id = e.id', 'INNER')
					->join('cancelled_res_tbl AS f', 'a.room_res_id = f.room_res_id', 'LEFT')
					->where('f.room_res_id IS NULL')
					->get();

		}
		else
		{
			$clause = array(
					'b.user_id' => $id
				);

			$query = $this->db->select($fields)
					->from('approved_res_tbl AS a')
					->join('room_res_tbl AS b', 'a.room_res_id = b.id', 'INNER')
					->join('users_tbl AS c', 'b.user_id = c.id', 'INNER')
					->join('room_tbl AS d', 'b.room_id = d.id', 'INNER')
					->join('users_tbl AS e', 'a.user_id = e.id', 'INNER')
					->join('cancelled_res_tbl AS f', 'a.room_res_id = f.room_res_id', 'LEFT')
					->where('f.room_res_id IS NULL')
					->where($clause)
					->get();
		}

		return $query->result();
	}

	public function store_disapproved_request($params)
	{
		$query = $this->db->insert('disapproved_res_tbl', $params);

		return $this;
		//var_dump($this->db->last_query());;
	}

	public function get_disapproved_request($id = 0)
	{
		$fields = array(
				'a.id',
				'a.room_res_id',
				'a.denied_datetime',
				'a.reason',
				'b.date_reserved',
				'b.purpose',
				'b.time_start',
				'b.time_end',
				'c.fullname',
				'd.room_no',
				'e.fullname AS approver'
			);

		if ($id == 0)
		{
			$query = $this->db->select($fields)
					->from('disapproved_res_tbl AS a')
					->join('room_res_tbl AS b', 'a.room_res_id = b.id', 'INNER')
					->join('users_tbl AS c', 'b.user_id = c.id', 'INNER')
					->join('room_tbl AS d', 'b.room_id = d.id', 'INNER')
					->join('users_tbl AS e', 'a.user_id = e.id', 'INNER')
					->get();
		}
		else
		{
			$clause = array(
					'b.user_id' => $id
				);

			$query = $this->db->select($fields)
					->from('disapproved_res_tbl AS a')
					->join('room_res_tbl AS b', 'a.room_res_id = b.id', 'INNER')
					->join('users_tbl AS c', 'b.user_id = c.id', 'INNER')
					->join('room_tbl AS d', 'b.room_id = d.id', 'INNER')
					->join('users_tbl AS e', 'a.user_id = e.id', 'INNER')
					->where($clause)
					->get();
		}

		return $query->result();
	}
}