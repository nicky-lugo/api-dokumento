<?php

namespace App\Models;

use CodeIgniter\Model;

class categoryModel extends Model
{
  protected $table = 'category';
  protected $primaryKey = 'id';
  protected $allowedFields = [
    'id',
    'name',
    'description',
    'created_date',
    'is_deleted',
    'changed_by_user'
  ];
  
   public function getList($data)
  {
	$search = $data['name'];
	$sort = $data['sort'];
    $sortorder = $data['sortorder'];
   
	$query =  $this->db->query('call Category_GetList("'.$search.'","'.$sort.'","'.$sortorder.'")');
	return $query->getResultArray();       
  }
  
   public function createData($data)
  {
	  $data = json_encode($data);
	  $query =  $this->db->query("call Category_Create('{$data}',@LID)");
	  $reasult =  $this->db->query('SELECT @LID as id');	  
	  
	  return $reasult->getResultArray();
  }
    public function updateData($data)
	{
	  
	  $data = json_encode($data);
	  $query 		=  $this->db->query("call Category_Update('{$data}')");
	  $reasult 			=  $this->db->affectedRows();
	  if($reasult){
		  $return = array('success'=>true);
	  } else {
		  $return = array('success'=>false);
	  }
	  return $return;
		
	}
  
  public function deleteData($data)
  {
	 $data = json_encode($data);
	  $query 		=  $this->db->query("call Category_Delete('{$data}')");
	  $reasult 			=  $this->db->affectedRows();
	  if($reasult){
		  $return = array('success'=>true);
	  } else {
		  $return = array('success'=>false);
	  }
	  return $return;
		
	}		   
  
  public function getData($data)
  {
	  $data = json_encode($data);
	  $query =  $this->db->query("call Category_GetData('{$data}')");
	  return $query->getRow();
  
 }
 
}