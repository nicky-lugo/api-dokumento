<?php namespace App\Controllers;

use \App\Libraries\Oauth;
use \OAuth2\Request;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use App\Models\UserloginModel;
use App\Models\UserGroupModel;

class User extends BaseController
{
	use ResponseTrait;
	protected $modelName = 'App\Models\UserModel';
	protected $format = 'json';
	public function refresh(){
		$oauth = new Oauth();
		$request = new Request();

		$respond = $oauth->server->handleTokenRequest($request->createFromGlobals());
		$code = $respond->getStatusCode();		
		$body = $respond->getResponseBody();
		return $this->respond($body, $code);
	}
	public function login(){
		try{
			// echo "<pre>";
			// print_r($this->password_hash('default101', PASSWORD_DEFAULT));
			// exit;
			$oauth = new Oauth();
			$request = new Request();
			$respond = $oauth->server->handleTokenRequest($request->createFromGlobals());

			$code = $respond->getStatusCode();		
			$body = $respond->getResponseBody();
			$IsSuccess = json_decode($body);
			if(isset($IsSuccess->error))
			{			
				return $this->respond(json_decode($body), $code);
			}
			
			
			$UserModel = new UserModel();
			$data = $UserModel->getUserByEmail($_POST['username']);
			$UserloginModel = new UserloginModel();
			$UserloginData = $UserloginModel->find($data[0]['ExternalId']);
			if(isset($_POST['post_cookie'])){
				$post_data = json_decode($_POST['post_cookie']);	
			}
			else{
				$post_data = array();
			}
			// $post_data = json_decode($_POST['post_cookie']);
			$data_fields = ['IsSuccess'	=> 1,
							  'IsMobile'	=> $this->request->getVar('device') == 0 ? 0:1,
							  'IsWebsite'	=> $this->request->getVar('device') == 1 ? 0:1,
							  'CreatedByUser'	=> $data[0]['UserId'],
							  'ChangedDuringVersion'	=> $this->request->getVar('version'),					  
							  'UserId'		=> $data[0]['UserId'],
							  'ExternalId'	=> $data[0]['ExternalId'],
							  'EmailAddress'=> $data[0]['UserName'],
							  'IpAddress'	=> $post_data->IP ?? '',
							  'IpAddressNumber'=> $post_data->IP ?? '',
							  'SessionId'	=> $post_data->Agent ?? '',
							  'UserAgent'	=> $post_data->Agent ?? '',
							  'LocalLanguage'=> $post_data->Language ?? '',
							  'LocalTime'	=> $post_data->Timezone ?? '',
							  'DisplayName'	=> $data[0]['DisplayName'],
							  'MobilePhoneNumber'=> $data[0]['MobilePhoneNumber'],
							  'PhotoUrl'	=> $data[0]['PhotoUrl'],
							  'ProviderId'	=> $data[0]['ProviderId'],
							  'Cookies'		=> $_POST['post_cookie']
							];
			$UserGroupModel = new UserGroupModel();
			$UserGroup = $UserGroupModel->getUserByUserID($data[0]['UserId']);
			
			$permission = ['roles' => $UserGroup->permission];

			$body = array_merge((array)json_decode($body),(array)$permission);
			
			// $post_id = $UserloginModel->createData($data_fields);
			$post_id = $UserloginModel->insert($data_fields);

		}catch (\Throwable  $e) {
			echo $e;
		}
		// print_r(unserialize($body['role']));
		// exit;
		return $this->respond($body, $code);
	}

	public function register(){
		helper('form');
		$data = [];
		$rules = [];

		if($this->request->getMethod() != 'post')
			return $this->fail('Only post request is allowed');

		///If ProviderId == null (registered using web/ not firebase login)
		if($this->request->getVar('ProviderId') == null ){
			$rules = [
				'UserName' 			=> 'required|valid_email|is_unique[users.UserName]',
				'Password' 			=> 'required|min_length[8]',
				'Password_confirm' 	=> 'matches[Password]',
			];
		}	
		else{
			///If ProviderId == gmail, email/password or phone (firebase login)
			$rules = ['AppStoreUserId' => 'required'];
		}
		
		// $password = password_hash($this->request->getVar('Password'), PASSWORD_BCRYPT);
		$password = $this->request->getVar('Password');

		if(! $this->validate($rules)){
			return $this->fail($this->validator->getErrors());

		}else{
			$model = new UserModel();
			$data = [
			'UserName' 			=> $this->request->getVar('UserName'),
			'phone' => $this->request->getVar('MobilePhoneNumber'),
			'email' 		=> $this->request->getVar('EmailAddress'),
			'IsEmailVerified' 	=> $this->request->getVar('EmailAddress'),
			
			'AppStoreUserId' 	=> $this->request->getVar('AppStoreUserId'),
			'DisplayName' 		=> $this->request->getVar('DisplayName'),
			'PhotoUrl' 			=> $this->request->getVar('PhotoUrl'),
			'ProviderId' 		=> $this->request->getVar('ProviderId'),
			'Password' 			=> $password,
			'scope' 			=> $this->request->getVar('scope'),
			// 'ExternalId'		=> strtoupper(md5(strtotime(date('Y-m-d H:i:s')).'ezGov2k20'))
			];

			$UserModel = new UserModel();
			$result = $UserModel->existing($data);
			if($result != null){
				$result['status'] = 'SUCCESS';
				$result['msg'] = "Account successfully registered.";
				return $this->respondCreated($result);
			}
			else{
				return $this->failResourceExists('Account already exist.');
			}
			
		}
	}

	public function getUserData(){
		$uid = $this->request->getVar('AppStoreUserId')?: '';

		$CitizenModel = new UserModel();
		$result = $CitizenModel->getUserData($uid);
		
		if($result != null){
			//$result['msg'] = "Existing record found.";
			return $this->respond($result);
		}
		else{
			return $this->failNotFound('User record not found.');
		}

	}	
	public function getUserDataByUserNamePass(){
		$user_name = $this->request->getVar('username')?: '';
		$password = $this->request->getVar('password')?: '';
		
		$UserModel = new UserModel();
		$result = $UserModel->getUserDataByUserNamePass($user_name);
		
		if($result != null){
			$results = [
				'status' => 'SUCCESS',
				'message' => 'Record(s) retrieved - 1',
				'record_count' => '1',
				'result' => $result
			];
			$hash_password = password_verify($password, $result['Password']);
			 if($hash_password === true) {
					return $this->respond($results);
			}
			else {
				$results = [
					'status' => 'SUCCESS',
					'message' => 'Incorect credentials',
					'record_count' => '0',
					'result' => ''
				];
				return $this->respond($results);
			}
			
			
		}
		else{
			$results = [
				'status' => 'SUCCESS',
				'message' => 'Incorect credentials',
				'record_count' => '0',
				'result' => ''
			];
			return $this->respond($results);			
		}

	}	
	public function checkUserName()	
	{
		$email = $_POST['email'];
		$UserModel = new UserModel();
		$data = $UserModel->checkUserName($email);
		print_r($data);
		
	}

}


