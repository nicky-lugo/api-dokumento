<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model{
  protected $table = 'users';
  protected $primaryKey = 'UserId';
  protected $allowedFields = ['ExternalId',
                              'UserName',
                              'Password',
                              'phone',
                              'email',
                              'account_id'
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
  // public function getUserByEmail($email){	
	// $user = $this->where(array("UserName"=>$email))
				// ->orWhere(array("email"=>$email))
				// ->orWhere(array("phone"=>$email))
				// ->findAll();
	  // return $user;
  // }
  public function getUserByEmail($email){	
	$user = $this->where(array("is_deleted <> "=>1))
				 ->where(array("UserName"=>$email))
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
    $uid = $data['ExternalId'];
    

    if($uid !=null){
      $user = $this->where(array("ExternalId"=>$uid))
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
					phone,
					DisplayName,
					image,
					SignatureUrl,
					ProviderId,
					Password,
					TwoFA,
          account_id,
          is_staff');
	$this->where('UserName',$user_name ); 
  $this->Where("is_deleted",0);
	// $this->orWhere(array("email"=>$user_name));	
	// $this->orWhere(array("phone"=>$user_name));	
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