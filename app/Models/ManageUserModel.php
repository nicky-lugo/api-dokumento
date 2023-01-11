<?php

namespace App\Models;

use CodeIgniter\Model;

class ManageUserModel extends Model
{
  protected $table = 'view_all_citizen';
  protected $primaryKey = 'CitizenID';
  protected $allowedFields = [
    'CitizenID',
    'FirstName',
	'MiddleName',   
    'LastName',
	'MobilePhoneNumber',
	'City',
	'sortOrder',
	'City',
    'CreatedByUser',
    'IsDeleted',
    'ChangedByUser'
  ];
  
  
   
	public function getList($data)
  {
	$search 	= $data['name'];
	$limit 	 	= $data['limit'];
    $offset 	= $data['offset'];
	$sort		= $data['sort'];
    $sortorder  = $data['sortorder'];
		   
			$query =  $this->db->query('call sp_list_manage_user("'.$search.'","'.$offset.'","'.$limit.'","'.$sort.'","'.$sortorder.'")');
			return $query->getResultArray();       
		  }
		  
   
  public function getData($data)
  {
	  $ExternalID 	= $data['ExternalID'];
	  $query =  $this->db->query('call sp_data_manage_user("'.$ExternalID.'")');
	  return $query->getRow();
  
 }
  
 public function updateMangeUserGroupName($data = NULL){
		$UserID 		= $data['UserID'];
		$GroupId 		= $data['GroupId'];
		$GroupName_val 	= $data['GroupName'];
		$this->db->query('call sp_update_manage_user_groupName("'.$UserID.'","'.$GroupId.'","'.$GroupName_val.'")');
		$reasult		=  $this->db->affectedRows();
		  if($reasult){
			  $return = array('success'=>true);
		  } else {
			  $return = array('success'=>false);
		  }
		  return $return;
	}
   
 public function updateUserPassword($data = NULL){
		$MainUserID 			= $data['MainUserID'];
		$UserID 		= $data['UserID'];
		$Password		= $data['Password'];
		$this->db->query('call sp_update_user_credential("'.$MainUserID.'","'.$UserID.'","'.$Password.'")');
		$reasult =  $this->db->affectedRows();
		  if($reasult){
			  $return = array('success'=>true);
		  } else {
			  $return = array('success'=>false);
		  }
		  return $return;
	}
  
  public function getCountMangeUser($data = NULL){
		$search	= $data['search'];	
		$query 	=  $this->db->query('call sp_count_manage_user("'.$search.'")');
		return $query->getResultArray();       		

	}
	
  public function InsertUserGroup($data)
  {
	  $ValUserID 		= $data['ValUserID'];
	  $ValGroupID		= $data['ValGroupID'];
	 
	  $query 	=  $this->db->query('call sp_insert_user_groups("'.$ValUserID.'","'.$ValGroupID.'",@LID)');
	  $reasult  =  $this->db->query('SELECT @LID as id');	  
	  return $reasult->getResultArray();
  }
}