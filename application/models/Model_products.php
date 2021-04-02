<?php 

class Model_products extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_users');
	}

	/* get the product data */
	public function getProductData($id = null)
	{
		if($id) {
			$sql = "select * from products where id = ? ORDER BY id";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "select * from products ORDER BY id";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/* get the product data */
	public function getProductDataByCat($cat_id = null)
	{
		if($cat_id) {

			$sql = "SELECT * FROM products where category_id = $cat_id";
			$query = $this->db->query($sql);
			$result = array();
			foreach($query->result_array() as $key => $value) {
				$category_ids = $value['category_id'];
				if($category_ids) {
					$result[] = $value;
				}
			}
			return $result;
		}	
	}

	public function getCategoryID($id)
	{
		if($id) {
			$sql = $this->db->query("SELECT category_id FROM products WHERE id = $id");
			return $sql->row_array();
		}
	}

	public function getActiveProductData()
	{
		$user_id = $this->session->userdata('id');

		if($user_id) {
			$sql = "SELECT category_id,price,id,name FROM products WHERE active = ? ORDER BY id";
			$query = $this->db->query($sql, array(1));
			return $query->result_array();
		}
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('products', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('products', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('products');
			return ($delete == true) ? true : false;
		}
	}

	public function countTotalProducts()
	{
		$sql = "SELECT * FROM products";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

}