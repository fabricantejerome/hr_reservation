<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	// Return user credentials
	public function exist()
	{
		$config = array(
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password')
			);

		$query = $this->db->select('a.id, a.username, a.password, a.fullname, a.email, b.role_id, c.user_type')
				->from('users_tbl AS a')
				->join('users_role_tbl AS b', 'a.id = b.user_id', 'INNER')
				->join('role_tbl AS c', 'b.role_id = c.id', 'INNER')
				->where($config)
				->get();

		if ($query->num_rows())
		{
			return $query->row_array();	
		}

		return false;
	}

}