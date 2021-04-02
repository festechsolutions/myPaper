<?php 

class Model_users extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getUserData($userId = null) 
	{
		if($userId) {
			$sql = "SELECT * FROM users WHERE id = ?";
			$query = $this->db->query($sql, array($userId));
			return $query->row_array();
		}

		$sql = "SELECT * FROM users WHERE id != ? ORDER BY id DESC";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function getUserGroup($userId = null) 
	{
		if($userId) {
			$sql = "SELECT * FROM user_group WHERE user_id = ?";
			$query = $this->db->query($sql, array($userId));
			$result = $query->row_array();

			$group_id = $result['group_id'];
			$g_sql = "SELECT * FROM groups WHERE id = ?";
			$g_query = $this->db->query($g_sql, array($group_id));
			$q_result = $g_query->row_array();
			return $q_result;
		}
	}

	public function getActiveUsersData($store_id)
	{
		if($store_id) {
			$sql = "SELECT id,username from users WHERE store_id = ? AND id > 5 AND subscribed = 2";
			$query = $this->db->query($sql, array($store_id));
			return $query->result_array();
		}

		$sql = "SELECT id,username from users WHERE store_id = ? AND id > 5 AND subscribed = 2 ORDER BY id DESC";
		$query = $this->db->query($sql, array($store_id));
		return $query->result_array();
	}

	public function getUsersData($store_id)
	{
		if($store_id) {
			$sql = "SELECT id,firstname,lastname from users WHERE store_id = ? AND id > 5";
			$query = $this->db->query($sql, array($store_id));
			return $query->result_array();
		}

		$sql = "SELECT id,username from users WHERE store_id = ? AND id > 5 ORDER BY id DESC";
		$query = $this->db->query($sql, array($store_id));
		return $query->result_array();
	}

	public function getSubscribedUsersData($store_id)
	{
		if($store_id) {
			$sql = "SELECT id,firstname,lastname from users WHERE store_id = ? AND id > 5 AND subscribed = 1";
			$query = $this->db->query($sql, array($store_id));
			return $query->result_array();
		}

		$sql = "SELECT id,firstname,lastname from users WHERE store_id = ? AND id > 5 AND subscribed = 1 ORDER BY id DESC";
		$query = $this->db->query($sql, array($store_id));
		return $query->result_array();
	}

	public function create($data = '', $group_id = null)
	{

		if($data && $group_id) {
			$create = $this->db->insert('users', $data);

			$user_id = $this->db->insert_id();

			$group_data = array(
				'user_id' => $user_id,
				'group_id' => $group_id
			);

			$group_data = $this->db->insert('user_group', $group_data);

			return ($create == true && $group_data) ? true : false;
		}
	}

	public function edit($data = array(), $id = null, $group_id = null)
	{
		$this->db->where('id', $id);
		$update = $this->db->update('users', $data);

		if($group_id) {
			// user group
			$update_user_group = array('group_id' => $group_id);
			$this->db->where('user_id', $id);
			$user_group = $this->db->update('user_group', $update_user_group);
			return ($update == true && $user_group == true) ? true : false;	
		}
			
		return ($update == true) ? true : false;	
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		$delete = $this->db->delete('users');
		return ($delete == true) ? true : false;
	}

	public function countTotalUsers()
	{
		$sql = $this->db->query("SELECT COUNT(*) AS count FROM users INNER JOIN user_group ON users.id = user_group.user_id WHERE user_group.group_id != '1' && user_group.group_id != '2'")->row();
		return $sql->count;
	}  

	public function countSubscribedUsers()
	{
		$sql = $this->db->query("SELECT COUNT(*) AS count FROM users INNER JOIN user_group ON users.id = user_group.user_id WHERE user_group.group_id != '1' && user_group.group_id != '2' && users.subscribed ='1'")->row();
		return $sql->count;
	}
	
}