<?php namespace App\Models;

use CodeIgniter\Model;

class UserGroupModel extends Model{
  protected $table = 'user_group';
  protected $primaryKey = 'id';
  protected $allowedFields = ['user_id',
                              'group_id'];

 
  public function getUserByUserID($userId){	
	 // $this->distinct();
    // $this->select('a.UserId,c.GroupName,c.Permission');
    // $this->from('users a'); 
    // $this->join('user_group b', 'b.UserID = a.UserId', 'LEFT');
    // $this->join('groups c', 'c.GroupId = b.GroupId', 'LEFT');
	// $this->where('a.UserId',$userId ); 	
    // $result = $this->first();
	
	// return $result;
	$query     =  $this->db->query('call User_GetByUserID("' . $userId . '")');
	return $query->getRow();
  }

  
}