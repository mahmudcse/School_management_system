<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Journaledit extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function save($data){

		$this->db->where('componentId', $data['transaction']['componentId']);
		$this->db->update('transaction',$data['transaction']);

		foreach ($data['details'] as $newRows) {
				if($newRows['componentId'] == ''){
					$this->db->insert('transaction_detail', $newRows);
				}
			}

		foreach($data['preComponentId'] as $predata){
			$match = 0;
			foreach($data['details'] as $newData){

					if($newData['componentId'] == $predata['componentId']){
						$match = 1;

						$this->db->where('componentId', $predata['componentId']);
						$this->db->update('transaction_detail',$newData);
						}
					
				}
				if($match != 1){
					$this->db->delete('transaction_detail',array('componentId'=>$predata['componentId']));
				}

			}
			$this->session->set_flashdata('flash_message' , get_phrase('data_updated_successfully'));
			redirect(base_url() . 'index.php?admin/journal', 'refresh');

			
	}
}

		// echo "<pre>";
		// print_r($data);
		// echo "</pre>";
		// exit();
