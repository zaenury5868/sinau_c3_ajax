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
			$row[] = '
				<a href="#" class="btn btn-success btn-sm" onClick="byid('."'".$result->id."', 'edit'".')">Edit</a>
				<a href="#" class="btn btn-danger btn-sm" onClick="byid('."'".$result->id."', 'delete'".')">Delete</a>
			';
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
	public function add()
	{
		$data = [
			'nama_depan' => htmlspecialchars($this->input->post('firstName')),
			'nama_belakang' => htmlspecialchars($this->input->post('lastName')),
			'alamat' => htmlspecialchars($this->input->post('address')),
			'no_hp' => htmlspecialchars($this->input->post('mobilePhoneNumber'))
		];

		if ($this->Serverside_model->create($data)> 0){
			$message['status'] = 'success';
		}else{
			$message['status'] = 'failed';
		};
		$this->output->set_content_type('application/json')->set_output(json_encode($message));
	}
	public function byid($id)
	{
		$data = $this->Serverside_model->getdataById($id);
		
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
		
	}
	public function update()
	{
		$data = [
			'nama_depan' => htmlspecialchars($this->input->post('firstName')),
			'nama_belakang' => htmlspecialchars($this->input->post('lastName')),
			'alamat' => htmlspecialchars($this->input->post('address')),
			'no_hp' => htmlspecialchars($this->input->post('mobilePhoneNumber'))
		];

		if ($this->Serverside_model->update(array('id' => $this->input->post('id')), $data)> 0){
			$message['status'] = 'success';
		}else{
			$message['status'] = 'failed';
		};
		$this->output->set_content_type('application/json')->set_output(json_encode($message));
	}
	
	public function delete($id)
	{
		if ($this->Serverside_model->delete($id)> 0){
			$message['status'] = 'success';
		}else{
			$message['status'] = 'failed';
		};
		$this->output->set_content_type('application/json')->set_output(json_encode($message));	
	}
}