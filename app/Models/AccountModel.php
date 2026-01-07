<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountModel extends Model
{
  protected $table = 'cs_account';
  protected $primaryKey = 'id';
  protected $allowedFields = [
    'id',
    'name',
    'owner_name',
	'email_address',
	'ticket_email_address',
	'product',
	'address',
	'status',
    'created_date',
    'is_deleted',
	'changed_by_date',
    'changed_by_user'
  ];
    
  
//   public function getData($data)
//   {
// 	  $data = json_encode($data);
// 	  $query =  $this->db->query("call Account_GetData('{$data}')");
// 	  return $query->getRow();
  
//  }


 public function getData($accountId){	
   $query     =  $this->db->query('call Account_GetData("' . $accountId . '")');
   return $query->getRow();
 }
 public function createData($data)
  {
	  print_r($data);
	  $data = json_encode($data);
	  $query =  $this->db->query("call Account_Create('{$data}',@LID)");
	  $reasult =  $this->db->query('SELECT @LID as id');	  
	  
	  return $reasult->getResultArray();
  }

 
}