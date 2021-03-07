<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Serverside_model extends CI_Model {

	var $table = 'karyawan';
	var $column_order = array('id', 'nama_depan', 'nama_belakang', 'alamat','no_hp');
	var $order = array('id', 'nama_depan', 'nama_belakang', 'alamat', 'no_hp');

	private function _get_data_query()
	{
		$this->db->from($this->table);
		if(isset($_POST['search']['value'])){
			$this->db->like('nama_depan', $_POST['search']['value']);
			$this->db->or_like('nama_belakang', $_POST['search']['value']);
			$this->db->or_like('alamat', $_POST['search']['value']);
			$this->db->or_like('no_hp', $_POST['search']['value']);
		}

		if (isset($_POST['order'])) {
			$this->db->order_by($this->order[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
		} else {
			$this->db->order_by('nama_depan', 'ASC');
		}
	}
	public function getDataTable()
	{
		$this->_get_data_query();
		if($_POST['length'] != -1){
			$this->db->limit($_POST['length'], $_POST['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function count_filtered_data()
	{
		$this->_get_data_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_data()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
}