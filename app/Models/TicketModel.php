<?php namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model{
  protected $table = 'ticket_head';
  protected $primaryKey = 'id';
  protected $allowedFields = [
    'id',
    'account_id',
    'user_id',
    'reference_number',
    'ticket_number',
    'subject',
	'description',
	'contact_id',
	'type_id',
	'source',
	'status_id',
	'priority_id',
	'state_id',
	'impact_id',
	'group_id',
	'capability_level_id',
	'agent_id',
	'tags',
	'watch_id',
	'division_id',
	'log_time',
	'response_agent_id',
	'response_time',
	'sort_order',
	'is_deleted',
	'created_date',
	'changed_date',
	'changed_by_user',
	'message_id',
	'product_id',
	'product_type_id',
	'upload_image'
  ];
  
   public function getList($data)
  {
	$search = $data['name'];
	$sort = $data['sort'];
    $sortorder = $data['sortorder'];
   
	$query =  $this->db->query('call Ticket_GetList("'.$search.'","'.$sort.'","'.$sortorder.'")');
	return $query->getResultArray();       
  }
  public function getDetailList($data)
  {
	$trans_type = $data['trans_type'];
	$account_id = $data['account_id'];
    $id = $data['id'];
   
	$query =  $this->db->query('call Ticket_GetDetailList("'.$trans_type.'","'.$account_id.'","'.$id.'")');
	return $query->getResultArray();       
  }
  public function getAgentList($data)
  {
	$data = json_encode($data);
	// echo"call Agent_GetList('{$data}')";
	$query =  $this->db->query("call Agent_GetList('{$data}')");
	return $query->getResultArray();       
  }
  public function getTypeList($data)
  {
	$data = json_encode($data);
	//echo"call RefType_GetList('{$data}')";
	$query =  $this->db->query("call RefType_GetList('{$data}')");
	return $query->getResultArray();       
  }
  public function getCapabilityList($data)
  {
	$data = json_encode($data);
	//echo"call RefType_GetList('{$data}')";
	$query =  $this->db->query("call RefCapability_GetList('{$data}')");
	return $query->getResultArray();       
  }
  public function getGroupList($data)
  {
	$data = json_encode($data);
	//echo"call RefType_GetList('{$data}')";
	$query =  $this->db->query("call RefGroup_GetList('{$data}')");
	return $query->getResultArray();       
  }
  public function getImpactList($data)
  {
	$data = json_encode($data);
	//echo"call RefType_GetList('{$data}')";
	$query =  $this->db->query("call RefImpact_GetList('{$data}')");
	return $query->getResultArray();       
  }
  public function getPriorityList($data)
  {
	$data = json_encode($data);
	//echo"call RefType_GetList('{$data}')";
	$query =  $this->db->query("call RefPriority_GetList('{$data}')");
	return $query->getResultArray();       
  }
  public function getStatusList($data)
  {
	$data = json_encode($data);
	//echo"call RefType_GetList('{$data}')";
	$query =  $this->db->query("call RefStatus_GetList('{$data}')");
	return $query->getResultArray();       
  }
  public function getProductList($data)
  {
	$data = json_encode($data);
	//echo"call RefType_GetList('{$data}')";
	$query =  $this->db->query("call RefProduct_GetList('{$data}')");
	return $query->getResultArray();       
  }
  public function getLastTicketDetails($data)
  {
	$ticket_id = $data['ticket_id'];
	//echo"call RefType_GetList('{$data}')";
	$query =  $this->db->query("call Ticket_GetLastDetails(".$ticket_id.")");
	return $query->getResultArray();       
  }
  
  
  public function getProductTypeList($data)
  {
	$data = json_encode($data);
	// print_r($data);
	//echo"call RefType_GetList('{$data}')";
	$query =  $this->db->query("call RefProductType_GetList('{$data}')");
	return $query->getResultArray();       
  }
  
   public function createData($data)
  {
	  $data = json_encode($data);
	  $query =  $this->db->query("call Ticket_Create('{$data}',@LID)");
	  $reasult =  $this->db->query('SELECT @LID as id');	  
	  
	  return $reasult->getResultArray();
  }
  public function insertTicketDetails($data)
  {
	  $data = json_encode($data);
	  $query =  $this->db->query("call Ticket_CreateDetails('{$data}',@LID)");
	  $reasult =  $this->db->query('SELECT @LID as id');	  
	  
	  return $reasult->getResultArray();
  }
  
    public function updateTicketData($data)
	{
	  
	  $data = json_encode($data);
	  $query 		=  $this->db->query("call Ticket_Image_Update('{$data}')");
	  $reasult 			=  $this->db->affectedRows();
	  if($reasult){
		  $return = array('success'=>true);
	  } else {
		  $return = array('success'=>false);
	  }
	  return $return;
		
	}
	public function updateTicketDetailsData($data)
	{
	  
	  $data = json_encode($data);
	  $query 		=  $this->db->query("call Ticket_details_Image_Update('{$data}')");
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
	  $query 		=  $this->db->query("call Division_Delete('{$data}')");
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
	  $query =  $this->db->query("call Division_GetData('{$data}')");
	  return $query->getRow();
  
 } 
}