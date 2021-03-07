<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Serverside extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Serverside_model');
	}
	
	public function index()
	{
		$this->load->view('serverside');
	}
	public function getData()
	{
		$results = $this->Serverside_model->getDataTable();
		$data = [];
		$no = $_POST['start'];
		foreach($results as $result) {
			$row = array();
			$row[] = ++$no;
			$row[] = $result->nama_depan;
			$row[] = $result->nama_belakang;
			$row[] = $result->alamat;
			$row[] = $result->no_hp;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Serverside_model->count_all_data(),
			"recordsFiltered" => $this->Serverside_model->count_filtered_data(),
			"data" => $data,
		);
		
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
		
	}
}