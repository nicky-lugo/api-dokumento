<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ticketModel;
use App\Models\UserModel;
use App\Models\LogModel;

class Ticket extends BaseController
{
	protected $modelName = 'App\Models\ticketModel';
	protected $format = 'json';


	public function createData(){
		helper('form');
		$data = [];
		
		$user_id = $this->request->getVar('user_id');
		
		if($this->request->getMethod() != 'post')
			return $this->fail('Only post request is allowed');

		if(ENABLE_LOGS){
			try{
				$logModel = new LogModel();
				$logData = [
					'UserID' => $user_id,
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
			'user_id' => 'required'
		];

		if(! $this->validate($rules)){
			return $this->fail($this->validator->getErrors());

		}else{
			
			$data = [
			'user_id' => $this->request->getVar('user_id'),
			'account_id' => $this->request->getVar('account_id'),
			'reference_number' => $this->request->getVar('reference_number'),			
			'ticket_number' => $this->request->getVar('ticket_number'),
			'subject' => $this->request->getVar('subject'),
			'description' => $this->request->getVar('description'),
			'contact_id' => $this->request->getVar('contact_id'),
			'type_id' => $this->request->getVar('type_id'),
			'source' => $this->request->getVar('source'),
			'status_id' => $this->request->getVar('status_id'),
			'priority_id' => $this->request->getVar('priority_id'),
			'state_id' => $this->request->getVar('state_id'),
			'impact_id' => $this->request->getVar('impact_id'),
			'group_id' => $this->request->getVar('group_id'),
			'capability_level_id' => $this->request->getVar('capability_level_id'),
			'agent_id' => $this->request->getVar('agent_id'),
			'tags' => $this->request->getVar('tags'),
			'watch_id' => $this->request->getVar('watch_id'),
			'division_id' => $this->request->getVar('division_id'),
			'log_time' => $this->request->getVar('log_time'),
			'response_agent_id' => $this->request->getVar('response_agent_id'),
			'response_time' => $this->request->getVar('response_time'),			
			'sort_order' => $this->request->getVar('sort_order'),			
			'is_deleted' => $this->request->getVar('is_deleted'),			
			'created_date' => $this->request->getVar('created_date'),			
			'changed_date' => $this->request->getVar('changed_date'),			
			'changed_by_user' => $this->request->getVar('changed_by_user'),			
			'message_id' => $this->request->getVar('message_id'),
			'product_id' => $this->request->getVar('product_id'),			
			'product_type_id' => $this->request->getVar('product_type_id'),			
			'upload_image' => $this->request->getVar('upload_image'),			
			];
			// $noticeModel = new NoticeModel();
			// $result = $noticeModel->createNotice($data);
			$groups = new ticketModel();
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
	public function insertTicketDetails(){
		helper('form');
		$data = [];
		
		$ticket_id = $this->request->getVar('ticket_id');
		
		if($this->request->getMethod() != 'post')
			return $this->fail('Only post request is allowed');

		if(ENABLE_LOGS){
			try{
				$logModel = new LogModel();
				$logData = [
					'UserID' => $ticket_id,
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
			'ticket_id' => 'required'
		];

		if(! $this->validate($rules)){
			return $this->fail($this->validator->getErrors());

		}else{
			
			$data = [
			'ticket_id' => $this->request->getVar('ticket_id'),
			'note' => $this->request->getVar('note'),
			'current_status_id' => $this->request->getVar('current_status_id'),			
			'previous_status_id' => $this->request->getVar('previous_status_id'),
			'minute_on_hold' => $this->request->getVar('minute_on_hold'),
			'changed_by_user' => $this->request->getVar('changed_by_user')
			
			];
			// $noticeModel = new NoticeModel();
			// $result = $noticeModel->createNotice($data);
			$groups = new ticketModel();
			$result = $groups->insertTicketDetails($data);
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
		$account_id = $this->request->getVar('account_id') ?: NULL;
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
			'account_id' => $account_id,			
		];

		$ticket = new ticketModel();
		$result = $ticket->getList($data);
		
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
	public function getDetailList(){
		$trans_type = $this->request->getVar('trans_type') ?: NULL;
		$account_id = $this->request->getVar('account_id')?: NULL;
		$id = $this->request->getVar('id') ?: NULL;
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
			'trans_type' => $trans_type,
			'id' => $id,
			'account_id' => $account_id,			
		];

		$ticket = new ticketModel();
		$result = $ticket->getDetailList($data);
		
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
public function getAgentList(){
		$UserID = $this->request->getVar('UserID') ?: NULL;
		$AccountID = $this->request->getVar('AccountID')?: NULL;
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
			'UserID' => $UserID,
			'AccountID' => $AccountID		
		];

		$ticket = new ticketModel();
		$result = $ticket->getAgentList($data);
		
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
	public function getTypeList(){
		$ID = $this->request->getVar('ID') ?: NULL;
		$AccountID = $this->request->getVar('AccountID')?: NULL;
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
			'ID' => $ID,
			'AccountID' => $AccountID		
		];

		$ticket = new ticketModel();
		$result = $ticket->getTypeList($data);
		
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
	public function getCapabilityList(){
		$ID = $this->request->getVar('ID') ?: NULL;
		$AccountID = $this->request->getVar('AccountID')?: NULL;
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
			'ID' => $ID,
			'AccountID' => $AccountID		
		];

		$ticket = new ticketModel();
		$result = $ticket->getCapabilityList($data);
		
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
	public function getGroupList(){
		$ID = $this->request->getVar('ID') ?: NULL;
		$AccountID = $this->request->getVar('AccountID')?: NULL;
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
			'ID' => $ID,
			'AccountID' => $AccountID		
		];

		$ticket = new ticketModel();
		
		$result = $ticket->getGroupList($data);
		
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
	public function getImpactList(){
		$ID = $this->request->getVar('ID') ?: NULL;
		$AccountID = $this->request->getVar('AccountID')?: NULL;
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
			'ID' => $ID,
			'AccountID' => $AccountID		
		];

		$ticket = new ticketModel();
		
		$result = $ticket->getImpactList($data);
		
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
	public function getPriorityList(){
		$ID = $this->request->getVar('ID') ?: NULL;
		$AccountID = $this->request->getVar('AccountID')?: NULL;
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
			'ID' => $ID,
			'AccountID' => $AccountID		
		];

		$ticket = new ticketModel();
		
		$result = $ticket->getPriorityList($data);
		
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
	public function getStatusList(){
		$ID = $this->request->getVar('ID') ?: NULL;
		$AccountID = $this->request->getVar('AccountID')?: NULL;
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
			'ID' => $ID,
			'AccountID' => $AccountID		
		];

		$ticket = new ticketModel();
		
		$result = $ticket->getStatusList($data);
		
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
	public function getProductList(){
		$ID = $this->request->getVar('ID') ?: NULL;
		$AccountID = $this->request->getVar('AccountID')?: NULL;
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
			'ID' => $ID,
			'AccountID' => $AccountID		
		];

		$ticket = new ticketModel();
		
		$result = $ticket->getProductList($data);
		
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
	public function getProductTypeList(){
		$ID = $this->request->getVar('ID') ?: NULL;
		$AccountID = $this->request->getVar('AccountID')?: NULL;
		$ProductID = $this->request->getVar('ProductID')?: NULL;
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
			'ID' => $ID,
			'AccountID' => $AccountID,
			'ProductID' => $ProductID		
		];

		$ticket = new ticketModel();
		
		$result = $ticket->getProductTypeList($data);
		
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
	public function updateTicketUploadData(){
		helper('form');
		$data = [];
		
		$ticket_number = $this->request->getVar('ticket_number');
		
		if($this->request->getMethod() != 'post')
			return $this->fail('Only post request is allowed');

		if(ENABLE_LOGS){
			try{
				$logModel = new LogModel();
				$logData = [
					'ticket_number' => $ticket_number,
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
			'ticket_number' => 'required'	
		];

		if(! $this->validate($rules)){
			return $this->fail($this->validator->getErrors());

		}else{
			
			$data = [			
			'ticket_number' => $this->request->getVar('ticket_number'),
			'upload_image' => $this->request->getVar('upload_image')
			];
			$ticket = new ticketModel();
			$result = $ticket->updateTicketData($data);
			
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
	public function updateTicketDetailsUploadData(){
		helper('form');
		$data = [];
		
		$id = $this->request->getVar('id');
		
		if($this->request->getMethod() != 'post')
			return $this->fail('Only post request is allowed');

		if(ENABLE_LOGS){
			try{
				$logModel = new LogModel();
				$logData = [
					'id' => $id,
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
			'id' => 'required'	
		];

		if(! $this->validate($rules)){
			return $this->fail($this->validator->getErrors());

		}else{
			
			$data = [			
			'id' => $this->request->getVar('id'),
			'upload_image' => $this->request->getVar('upload_image')
			];
			$ticket = new ticketModel();
			$result = $ticket->updateTicketDetailsData($data);
			
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
	public function getLastTicketDetails(){
		$ticket_id = $this->request->getVar('ticket_id')?: NULL;
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
			'ticket_id' => $ticket_id,			
		];

		$ticket = new ticketModel();
		
		$result = $ticket->getLastTicketDetails($data);
		
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
}

