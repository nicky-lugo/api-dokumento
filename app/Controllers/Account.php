<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\AccountModel;
use App\Models\UserModel;
use App\Models\LogModel;

class Account extends BaseController
{
	protected $modelName = 'App\Models\AccountModel';
	protected $format = 'json';


	public function createData(){
		helper('form');
		$data = [];
		
		$external_id = $this->request->getVar('external_id');
		
		if($this->request->getMethod() != 'post')
			return $this->fail('Only post request is allowed');

		if(ENABLE_LOGS){
			try{
				$logModel = new LogModel();
				$logData = [
					'external_id' => $external_id,
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
			'external_id' => 'required'
		];

		if(! $this->validate($rules)){
			return $this->fail($this->validator->getErrors());

		}else{
			
			$data = [
			'external_id' => $this->request->getVar('external_id'),					
			'name' => $this->request->getVar('name'),					
			'owner_name' => $this->request->getVar('owner_name'),					
			'email_address' => $this->request->getVar('email_address'),					
			'address' => $this->request->getVar('address'),					
			'status' => $this->request->getVar('status'),					
			'is_deleted' => $this->request->getVar('is_deleted'),					
			'product' => $this->request->getVar('product'),					
			'ticket_email_address' => $this->request->getVar('ticket_email_address')
			];
			// $noticeModel = new NoticeModel();
			// $result = $noticeModel->createNotice($data);
			$account = new AccountModel();
			$result = $account->createData($data);
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

		$groups = new groupsModel();
		$result = $groups->getList($data);
		
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
			'GroupId' => 'required',
			'GroupName' => 'required',
			'Permission' => 'required',
			'UserID' => 'required',			
		];

		if(! $this->validate($rules)){
			return $this->fail($this->validator->getErrors());

		}else{
			
			$data = [
				
			'ChangedByUser' => $userID,
			'GroupId' => $this->request->getVar('GroupId'),
			'GroupName' => $this->request->getVar('GroupName'),
			'Permission' => $this->request->getVar('Permission'),
			];
			$groups = new groupsModel();
			$result = $groups->updateData($data);
			
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
			'GroupId' => 'required'
		];

		if(! $this->validate($rules)){
			return $this->fail($this->validator->getErrors());

		}else{
			
			$data = ['ChangedByUser' => $userID,
					'GroupId' => $this->request->getVar('GroupId'),			
			];
			$groups = new groupsModel();
			$result = $groups->deleteData($data);
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
		$GroupId = $this->request->getVar('GroupId') ?: 'NULL';
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
			'GroupId' => 'required',			
			'UserID' => 'required',			
		];

		if(! $this->validate($rules)){
			return $this->fail($this->validator->getErrors());

		}else{
			
			$data = ['GroupId' => $GroupId];
					
			$groups = new groupsModel();
			$result = $groups->getData($data);
			
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

