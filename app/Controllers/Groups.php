<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\groupsModel;
use App\Models\UserModel;
use App\Models\LogModel;

class Groups extends BaseController
{
	protected $modelName = 'App\Models\groupsModel';
	protected $format = 'json';


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
			'GroupName' => 'required',
			'Permission' => 'required',
			'UserID' => 'required',			
		];

		if(! $this->validate($rules)){
			return $this->fail($this->validator->getErrors());

		}else{
			
			$data = [
			'GroupName' => $this->request->getVar('GroupName'),
			'Permission' => $this->request->getVar('Permission'),
			'CreatedByUser' => $userID,
			];
			// $noticeModel = new NoticeModel();
			// $result = $noticeModel->createNotice($data);
			$groups = new groupsModel();
			$result = $groups->createData($data);
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

