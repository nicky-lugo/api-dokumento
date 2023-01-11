<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model{
  protected $table = 'users';
  protected $primaryKey = 'UserId';
  protected $allowedFields = ['ExternalId',
                              'UserName',
                              'Password',
                              'AppStoreUserId',
                              'MobilePhoneNumber',
                              'EmailAddress',
                              'DisplayName',
                              'ProviderId',
                              'IsEmailVerified',
                               ];

  protected $beforeInsert = ['beforeInsert'];
  protected $beforeUpdate = ['beforeUpdate'];


  protected function beforeInsert(array $data){
    $data = $this->passwordHash($data);
    return $data;
  }

  protected function beforeUpdate(array $data){
    $data = $this->passwordHash($data);
    return $data;
  }

  protected function passwordHash(array $data){
    if(isset($data['data']['Password']))
      $data['data']['Password'] = password_hash($data['data']['Password'], PASSWORD_DEFAULT);

    return $data;
  }
  public function getUserByEmail($email){	
	$user = $this->where(array("UserName"=>$email))
				->orWhere(array("EmailAddress"=>$email))
				->orWhere(array("MobilePhoneNumber"=>$email))
				->findAll();
	  return $user;
  }

  public function getUserId($uid =null){
    if($uid!=null){
      $res = $this->where('AppStoreUserId', $uid)->first();
      return $res!=null?$res['UserId']:null;
    }
    return null;
  }

  public function IsRegisteredUser($UserId=null){
    if($UserId!=null){
      $res = $this->where('UserId', $UserId)->first();
      if($res != null){
        return $res['UserId'];
      }
      return null;
      
    }
    return null;
  }
  
  public function existing(array $data){
    $uid = $data['AppStoreUserId'];
    $provider = $data['ProviderId'];

    if($uid !=null && $provider != null){
      $user = $this->where(array("AppStoreUserId"=>$uid, "ProviderId"=>$provider))
      ->findAll();
      //echo $this->db->getLastQuery(); 
    }
    else{
      $user =  false;
    }
    
     if($user){
      return null;
     }
     else{
      if($data['UserName'] == null){
        $data['UserName'] =$uid;
      }
      if($data['Password'] == null){
        $data['Password'] = $uid;
      }
      $data['ExternalId'] = strtoupper(md5(strtotime(date('Y-m-d H:i:s')).'ezGov2k20'));

      $user_id = $this->insert($data);
      //unset($data['Password']);
      unset($data);
      $data['id'] = $user_id;

      return  $data;
     }

  }

  public function getUserData($uid){
    
    $user = $this->where('AppStoreUserId', $uid)
                  ->first();
    unset($user['UserId']);
    return $user != null ? $user : null;
    
  }
  
  public function getUserDataByUserNamePass($user_name = NULL){
    
    $this->select('UserId, 
					ExternalId, 
					UserName, 
					DisplayName, 
					AppStoreUserId, 
					MobilePhoneNumber,
					DisplayName,
					PhotoUrl,
					SignatureUrl,
					ProviderId,
					Password,
					TwoFA');
	$this->where('UserName',$user_name ); 
	$this->orWhere(array("EmailAddress"=>$user_name));	
	$this->orWhere(array("MobilePhoneNumber"=>$user_name));	
	$result = $this->first();
	
	// echo $this->db->getLastQuery();    
    return $result;
    
  }
  public function checkUserName($email){	
	
	$query     =  $this->db->query('call Auth_CheckUserName("' . $email . '")');
	// echo $this->db->getLastQuery();    
    return $query->getResultArray();
	
  }
}