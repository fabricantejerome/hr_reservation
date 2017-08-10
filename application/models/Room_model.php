<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function browse($visible = 1)
	{
		if ($visible)
		{
			$query = $this->db->get('room_tbl');
		}
		else
		{
			$query = $this->db->get_where('room_tbl', array('available' => 1));
		}

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
				'room_name'   => $this->input->post('room_name'),
				'capacity'    => $this->input->post('capacity'),
				'description' => $this->input->post('description'),
				'available'   => $this->input->post('available') ? 1 : 0,
				'floor'       => $this->input->post('floor')
			);

		$id = $this->input->post('id');

		if ( $id == 0) {
			$this->db->insert('room_tbl', $data);
		}
		else {

			$this->db->update('room_tbl', $data, array('id' => $id));
		}

		return $this;
	}

	public function delete($id)
	{
		$this->db->delete('room_tbl', array('id' => $id));

		return $this;
	}

	public function store_reservation($params)
	{
		$id = $this->input->post('id');

		if ($id > 0)
		{
			$this->db->update('room_res_tbl', $params, array('id' => $id));

			return $id;
		}
		else 
		{
			$this->db->insert('room_res_tbl', $params);

			return $this->db->insert_id();
		}
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
				->join('disapproved_res_tbl AS c', 'b.id = c.room_res_id', 'LEFT')
				->join('cancelled_res_tbl AS d', 'b.id = d.room_res_id', 'LEFT')
				->where('c.room_res_id IS NULL')
				->where('d.room_res_id IS NULL')
				->where($params)
				->get();

		return $query->result();
	}

	public function fetch_block_time($params)
	{
		$fields = array(
				'b.time_start',
				'b.time_end'
			);

		$query = $this->db->select($fields)
				->from('approved_res_tbl AS a')
				->join('room_res_tbl AS b', 'a.room_res_id = b.id', 'INNER')
				->join('room_tbl AS e', 'b.room_id = e.id', 'INNER')
				->join('disapproved_res_tbl AS c', 'b.id = c.room_res_id', 'LEFT')
				->join('cancelled_res_tbl AS d', 'b.id = d.room_res_id', 'LEFT')
				->where('c.room_res_id IS NULL')
				->where('d.room_res_id IS NULL')
				->where($params)
				->get();

		return $query->result_array();
	}

	public function get_taken_slot($params)
	{
		$fields = array(
				'a.id',
				'a.room_res_id',
				'a.approved_datetime',
				'a.user_id AS approver_id',
				'b.date_reserved',
				'b.purpose',
				'b.time_start',
				'b.time_end',
				'b.user_id',
				'd.room_no',
				'd.room_name',
				'd.capacity',
				'd.floor'
			);

		$query = $this->db->select($fields)
				->from('approved_res_tbl AS a')
				->join('room_res_tbl AS b', 'a.room_res_id = b.id', 'INNER')
				->join('room_tbl AS d', 'b.room_id = d.id', 'INNER')
				->join('cancelled_res_tbl AS f', 'a.room_res_id = f.room_res_id', 'LEFT')
				->where('f.room_res_id IS NULL')
				->where($params)
				->get();

		return $query->result();
	}

	public function get_pending_request($id = 0)
	{
		$fields = array(
				'a.id',
				'a.purpose',
				'a.date_reserved',
				'a.user_id',
				'a.time_start',
				'a.time_end',
				'a.date_filed',
				'd.room_no',
				'd.room_name',
				'd.capacity',
				'd.floor'
			);

		if ($id == 0)
		{
			$query = $this->db->select($fields)
					->from('room_res_tbl AS a')
					->join('approved_res_tbl AS b', 'a.id = b.room_res_id', 'LEFT')
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
					->join('room_tbl AS d', 'a.room_id = d.id',  'INNER')
					->join('disapproved_res_tbl AS e', 'a.id = e.room_res_id', 'LEFT')
					->join('cancelled_res_tbl AS f', 'a.id = f.room_res_id', 'LEFT')
					->where('b.room_res_id IS NULL')
					->where('e.room_res_id IS NULL')
					->where('f.room_res_id IS NULL')
					->where($clause)
					->get();
		}
		
		return $query->result_array();
	}

	public function read_pending_request($id = 0)
	{
		$fields = array(
				'a.id',
				'a.purpose',
				'a.date_reserved',
				'a.time_start',
				'a.time_end',
				'a.date_filed',
				'a.room_id',
				'a.user_id',
				'd.room_no',
				'd.room_name',
				'd.capacity',
				'd.floor'
			);

		if ($id > 0) {
			$clause = array(
					'a.id' => $id
				);

			$query = $this->db->select($fields)
					->from('room_res_tbl AS a')
					->join('approved_res_tbl AS b', 'a.id = b.room_res_id', 'LEFT')
					->join('room_tbl AS d', 'a.room_id = d.id',  'INNER')
					->join('disapproved_res_tbl AS e', 'a.id = e.room_res_id', 'LEFT')
					->join('cancelled_res_tbl AS f', 'a.id = f.room_res_id', 'LEFT')
					->where('b.room_res_id IS NULL')
					->where('e.room_res_id IS NULL')
					->where('f.room_res_id IS NULL')
					->where($clause)
					->get();

			return $query->row_array();
		}
	}

	public function store_approved_request($params)
	{
		$this->db->insert('approved_res_tbl', $params);
	}

	public function get_approved_request($id = 0)
	{
		
		$fields = array(
				'a.id',
				'a.room_res_id',
				'a.approved_datetime',
				'a.user_id AS approver_id',
				'b.date_reserved',
				'b.purpose',
				'b.time_start',
				'b.time_end',
				'b.user_id',
				'd.room_no',
				'd.room_name',
				'd.capacity',
				'd.floor'
			);

		if ($id == 0)
		{
			$query = $this->db->select($fields)
					->from('approved_res_tbl AS a')
					->join('room_res_tbl AS b', 'a.room_res_id = b.id', 'INNER')
					->join('room_tbl AS d', 'b.room_id = d.id', 'INNER')
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
					->join('room_tbl AS d', 'b.room_id = d.id', 'INNER')
					->join('cancelled_res_tbl AS f', 'a.room_res_id = f.room_res_id', 'LEFT')
					->where('f.room_res_id IS NULL')
					->where($clause)
					->get();
		}

		return $query->result_array();
	}

	public function fetch_approved_request()
	{
		$fields = array(
				'a.id',
				'a.room_res_id',
				'a.approved_datetime',
				'a.user_id AS approver_id',
				'b.date_reserved',
				'b.purpose',
				'b.time_start',
				'b.time_end',
				'b.user_id',
				'd.room_no',
				'd.room_name',
				'd.capacity',
				'd.floor'
			);

		$query = $this->db->select($fields)
				->from('approved_res_tbl AS a')
				->join('room_res_tbl AS b', 'a.room_res_id = b.id', 'INNER')
				->join('room_tbl AS d', 'b.room_id = d.id', 'INNER')
				->join('cancelled_res_tbl AS f', 'a.room_res_id = f.room_res_id', 'LEFT')
				->where('f.room_res_id IS NULL')
				->where('b.date_reserved >=', date('Y-m-d'))
				->get();

		return $query->result_array();
	}

	public function read_approved_request($id = 0)
	{
		
		$fields = array(
				'a.id',
				'a.room_res_id',
				'a.approved_datetime',
				'a.user_id AS approver_id',
				'b.date_reserved',
				'b.purpose',
				'b.time_start',
				'b.time_end',
				'b.user_id',
				'd.room_no',
				'd.room_name',
				'd.capacity',
				'd.floor'
			);

		if ($id > 0)
		{
			$clause = array(
					'a.room_res_id' => $id
				);

			$query = $this->db->select($fields)
					->from('approved_res_tbl AS a')
					->join('room_res_tbl AS b', 'a.room_res_id = b.id', 'INNER')
					->join('room_tbl AS d', 'b.room_id = d.id', 'INNER')
					->join('cancelled_res_tbl AS f', 'a.room_res_id = f.room_res_id', 'LEFT')
					->where('f.room_res_id IS NULL')
					->where($clause)
					->get();

			return $query->row_array();
		}
	}

	public function store_disapproved_request($params)
	{
		$query = $this->db->insert('disapproved_res_tbl', $params);

		return $this;
	}

	public function get_disapproved_request($id = 0)
	{
		$fields = array(
				'a.id',
				'a.room_res_id',
				'a.denied_datetime',
				'a.reason',
				'a.user_id AS approver_id',
				'b.date_reserved',
				'b.purpose',
				'b.time_start',
				'b.time_end',
				'b.user_id',
				'd.room_no',
				'd.room_name',
				'd.capacity',
				'd.floor'
			);

		if ($id == 0)
		{
			$query = $this->db->select($fields)
					->from('disapproved_res_tbl AS a')
					->join('room_res_tbl AS b', 'a.room_res_id = b.id', 'INNER')
					->join('room_tbl AS d', 'b.room_id = d.id', 'INNER')
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
					->join('room_tbl AS d', 'b.room_id = d.id', 'INNER')
					->where($clause)
					->get();
		}

		return $query->result_array();
	}

	public function read_disapproved_request($id = 0)
	{
		$fields = array(
				'a.id',
				'a.room_res_id',
				'a.denied_datetime',
				'a.reason',
				'a.user_id AS approver_id',
				'b.date_reserved',
				'b.purpose',
				'b.time_start',
				'b.time_end',
				'b.user_id',
				'd.room_no',
				'd.room_name',
				'd.capacity',
				'd.floor'
			);

		if ($id > 0)
		{
			$clause = array(
					'a.room_res_id' => $id
				);

			$query = $this->db->select($fields)
					->from('disapproved_res_tbl AS a')
					->join('room_res_tbl AS b', 'a.room_res_id = b.id', 'INNER')
					->join('room_tbl AS d', 'b.room_id = d.id', 'INNER')
					->where($clause)
					->get();
		}

		return $query->row_array();
	}

	public function store_cancel_request($params)
	{
		$this->db->insert('cancelled_res_tbl', $params);

		return $this;
	}

	public function get_cancelled_request($id = 0)
	{
		$fields = array(
				'a.id',
				'a.room_res_id',
				'a.cancelled_datetime',
				'a.reason',
				'a.user_id AS approver_id',
				'b.date_reserved',
				'b.purpose',
				'b.time_start',
				'b.time_end',
				'b.user_id',
				'd.room_no',
				'd.room_name',
				'd.capacity',
				'd.floor'
			);

		if ($id == 0)
		{
			$query = $this->db->select($fields)
					->from('cancelled_res_tbl AS a')
					->join('room_res_tbl AS b', 'a.room_res_id = b.id', 'INNER')
					->join('room_tbl AS d', 'b.room_id = d.id', 'INNER')
					->get();
		}
		else
		{
			$clause = array(
					'b.user_id' => $id
				);

			$query = $this->db->select($fields)
					->from('cancelled_res_tbl AS a')
					->join('room_res_tbl AS b', 'a.room_res_id = b.id', 'INNER')
					->join('room_tbl AS d', 'b.room_id = d.id', 'INNER')
					->where($clause)
					->get();
		}

		return $query->result_array();
	}

	public function read_cancelled_request($id = 0)
	{
		$fields = array(
				'a.id',
				'a.room_res_id',
				'a.cancelled_datetime',
				'a.reason',
				'a.user_id AS approver_id',
				'b.date_reserved',
				'b.purpose',
				'b.time_start',
				'b.time_end',
				'b.user_id',
				'd.room_no',
				'd.room_name',
				'd.capacity',
				'd.floor'
			);

		if ($id > 0)
		{
			$clause = array(
					'a.room_res_id' => $id
				);

			$query = $this->db->select($fields)
					->from('cancelled_res_tbl AS a')
					->join('room_res_tbl AS b', 'a.room_res_id = b.id', 'INNER')
					->join('room_tbl AS d', 'b.room_id = d.id', 'INNER')
					->where($clause)
					->get();

			return $query->row_array();
		}
	}
}