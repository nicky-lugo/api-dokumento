<?php

namespace App\Models;

use CodeIgniter\Model;

class LogModel extends Model
{
  protected $table = 'logs';
  protected $primaryKey = 'ID';
  protected $allowedFields = [
    'ID',
    'UserID',
    'LogAction',
    'UserType',
    'LogModule',
    'Data',
    'DateTime'
  ];

  public function create(array $data)
  {
	
    $log_id = $this->insert($data);
    $data['id'] = $log_id;
    
    // $fp = fopen('data.txt', 'a');//opens file in append mode  
    // fwrite($fp, $this->db->getLastQuery());
    // fclose($fp); 
    return  $data;
  }
}
