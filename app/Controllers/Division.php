<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\divisionModel;
use App\Models\UserModel;
use App\Models\LogModel;

class Division extends BaseController
{
	protected $modelName = 'App\Models\divisionModel';
	protected $format = 'json';

	public function getList(){
		$name = $this->request->getVar('search') ?: NULL;
		$sort = $this->request->getVar('sort')?: NULL;
		$sortorder = $this->request->getVar('sortorder') ?: NULL;
		$userID = $this->request->getVar('UserID');
		if(ENABLE_LOGS){
			try{
				$logModel = new LogModel();
				$logData = [
					'UserID' => $userID,
					'LogAction' => 'View',
					'UserType' => 'Web',
					'LogModule' => __CLASS__.'\\'. __FUNCTION__,
					'Data' => json_encode($this->request->getVar()),
					];
				$logModel->create($logData);
			}
			catch (\Throwable  $e) {
			   //print_r($e);
		   }
		}	
		$data = [
			'name' => $name,
			'sort' => $sort,
			'sortorder' => $sortorder,
		];

		$division = new divisionModel();
		$result = $division->getList($data);
		
		$response['status'] = 'SUCCESS';
		$response['message'] = 'Record(s) retrieved - '.count($result);
		$response['record_count'] = count($result);
		$response['result']  = [];

		if($result != null){
			$response['msg'] = "Notice data successfully retrieved.";
			$response['status'] = 'SUCCESS';
			$response['result']  = $result;
		}
		return $this->respond($response);
		
	}
	
	public function createData(){
		helper('form');
		$data = [];
		
		$userID = $this->request->getVar('UserID');
		
		if($this->request->getMethod() != 'post')
			return $this->fail('Only post request is allowed');

		if(ENABLE_LOGS){
			try{
				$logModel = new LogModel();
				$logData = [
					'UserID' => $userID,
					'LogAction' => 'Add',
					'UserType' => 'Web',
					'LogModule' => __CLASS__.'\\'. __FUNCTION__,
					'Data' => json_encode($this->request->getVar()),
					];
				$logModel->create($logData);
			}
			catch (\Throwable  $e) {
			   //print_r($e);
		   }
		}

		$rules = [
			'DivisionName' => 'required',
			'Description' => 'required',
			'UserID' => 'required',			
		];

		if(! $this->validate($rules)){
			return $this->fail($this->validator->getErrors());

		}else{
			
			$data = [
			'name' => $this->request->getVar('DivisionName'),
			'description' => $this->request->getVar('Description'),
			'account_id' => $userID,
			];
			$division = new divisionModel();
			$result = $division->createData($data);
			if($result != null){
				$result['msg'] = "Data successfully added.";
				$result['status'] = 'SUCCESS';
				return $this->respondCreated($result);
			}
			else{
				return $this->fail('Unexpected Error Occurs.');
			}
			
		}
	}

	

	public function updateData(){
		helper('form');
		$data = [];
		
		$userID = $this->request->getVar('UserID');
		
		if($this->request->getMethod() != 'post')
			return $this->fail('Only post request is allowed');

		if(ENABLE_LOGS){
			try{
				$logModel = new LogModel();
				$logData = [
					'UserID' => $userID,
					'LogAction' => 'Update',
					'UserType' => 'Web',
					'LogModule' => __CLASS__.'\\'. __FUNCTION__,
					'Data' => json_encode($this->request->getVar()),
					];
				$logModel->create($logData);
			}
			catch (\Throwable  $e) {
			   //print_r($e);
		   }
		}

		$rules = [
			'DivisionID' => 'required',
			'Name' => 'required',
			'Description' => 'required',
			'UserID' => 'required',			
		];

		if(! $this->validate($rules)){
			return $this->fail($this->validator->getErrors());

		}else{
			
			$data = [
			'id' => $this->request->getVar('DivisionID'),	
			'name' => $this->request->getVar('Name'),
			'description' => $this->request->getVar('Description'),
			'changed_by_user' => $userID,						
			'changed_date' => date('Y-m-d H:i:s'),
			];
			$division = new divisionModel();
			$result = $division->updateData($data);
			
			if($result != null){
				$result['msg'] = $result['success'] == true ? "Data successfully updated.":"Data not updated.";
				$result['status'] = $result['success'] == true ? 'SUCCESS':'FAILED';
				return $this->respondCreated($result);
			}
			else{
				return $this->fail('Unexpected Error Occurs.');
			}
			
		}

	}	


	public function deleteData(){

		helper('form');
		$data = [];
		
		$userID = $this->request->getVar('UserID');
		
		if($this->request->getMethod() != 'post')
			return $this->fail('Only post request is allowed');

		if(ENABLE_LOGS){
			try{
				$logModel = new LogModel();
				$logData = [
					'UserID' => $userID,
					'LogAction' => 'Delete',
					'UserType' => 'Web',
					'LogModule' => __CLASS__.'\\'. __FUNCTION__,
					'Data' => json_encode($this->request->getVar()),
					];
				$logModel->create($logData);
			}
			catch (\Throwable  $e) {
			   //print_r($e);
		   }
		}

		$rules = [
			'DivisionID' => 'required',
			'UserID' => 'required',			
		];

		if(! $this->validate($rules)){
			return $this->fail($this->validator->getErrors());

		}else{
			
			$data = ['changed_by_user' => $userID,
					'id' => $this->request->getVar('DivisionID')					
			];
			$division = new divisionModel();
			$result = $division->deleteData($data);
			if($result != null){
				$result['msg'] = $result['success'] == true ? "Data successfully deleted.":"Data not deleted.";
				$result['status'] = $result['success'] == true ? 'SUCCESS':'FAILED';
				return $this->respondCreated($result);
			}
			else{
				return $this->fail('Unexpected Error Occurs.');
			}
			
		}
	}

	public function getData(){		
		$DivisionID = $this->request->getVar('DivisionID') ?: 'NULL';
		$userID = $this->request->getVar('UserID');
		
		if($this->request->getMethod() != 'post')
			return $this->fail('Only post request is allowed');	
		
		if(ENABLE_LOGS){
			try{
				$logModel = new LogModel();
				$logData = [
					'UserID' => $userID,
					'LogAction' => 'View',
					'UserType' => 'Web',
					'LogModule' => __CLASS__.'\\'. __FUNCTION__,
					'Data' => json_encode($this->request->getVar()),
					];
				$logModel->create($logData);
			}
			catch (\Throwable  $e) {
			   //print_r($e);
		   }
		}	
			
		$rules = [
			'DivisionID' => 'required',			
			'UserID' => 'required',			
		];

		if(! $this->validate($rules)){
			return $this->fail($this->validator->getErrors());

		}else{
			
			$data = ['id' => $CategoryID];
					
			$division = new divisionModel();
			$result = $division->getData($data);
			
			if($result != null){
				
				$response['result']  = $result;
				$response['status'] = 'SUCCESS';
				$response['msg'] = "Data successfully retrieved.";

				return $this->respond($response);
			}
			else{
				return $this->failNotFound('Data record not found.');
			}
			
		}



}
}

