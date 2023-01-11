<?php namespace App\Models;

use CodeIgniter\Model;

class VersionModel extends Model{
  protected $table = 'ref_app_version';
  protected $primaryKey = 'VersionId';
  protected $allowedFields = ['VersionId',
                              'AppName',
                              'PackageName',
                              'Version',
                              'BuildNumber',
                              'CreatedByUser',
                              'IsDeleted',
                              'ChangedByUser'];

  public function createVersion(array $data){
    $version_id = $this->insert($data);
    $data['id'] = $version_id;
    return  $data;
  }  

  
	public function updateVersion(array $data){
    $versionID = $data['VersionId'];
    $version = $this->where('VersionId', $versionID)
    ->first();
    
    if($version){
      
      $version_id = $this->update($version['VersionId'],$data);
      unset($data);
      $data['id'] = $version_id;
      return  $data;
    }
    else{
      return null;
    }
  }

  public function deleteData(array $data){
    $existing = $this->where('VersionId', $data['VersionId'])
    ->first();
    
    if($existing != null){
      //echo $this->db->getLastQuery();
      $version_id = $this->update($data['VersionId'],$data);
      //echo $this->db->getLastQuery();
            unset($data);
      $data['id'] = $version_id;
      return  $data;
      
    }
    else{
      return null;
    }
  }  

}

