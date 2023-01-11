<?php

namespace App\Models;

use CodeIgniter\Model;

class UploadModel extends Model
{
  protected $table = 'uploaded_files';
  protected $primaryKey = 'UploadedFileId';
  protected $allowedFields = [
    'UploadedFileId',
    'ExternalId',
    'AppStoreUserId',
    'TransactionId',
    'VerificationType',
    'FilePath',
    'UploadedFileTypeId',
    'IdentificationTypeId',
    'DocumentTypeId',
    'IdentificationVerificationTypeId',
    'OCR',
    'CreatedByUser',
    'IsDeleted',
    'ChangedByUser'
  ];


  public function getUploadList($data)
  {
    $limit = $data['limit'];
    $offset = $data['offset'];
    $sort = $data['sort'];
    $sortorder = $data['sortorder'];
    $verificationType = $data['verificationType'];
    $userID = $data['userid'];
    $uid = $data['uid'];
    $externalID = $data['externalID'];
    $ocr = $data['ocr'];
    $transactionId = $data['TransactionId'];

    $this->distinct();
    $this->select('a.*, b.Name AS FileType, c.Name AS IDType, d.Name AS DocumentType');

    $this->from('uploaded_files a');
    $this->join('ref_uploaded_file_types b', 'b.UploadedFileTypeId = a.UploadedFileTypeId', 'LEFT');
    $this->join('ref_identification_types c', 'c.IdentificationTypeId = a.IdentificationTypeId', 'LEFT');
    $this->join('ref_document_types d', 'd.DocumentTypeId = a.DocumentTypeId', 'LEFT');
    //$this->join('ref_identification_verification_types e', 'e.IdentificationVerificationTypeId = a.IdentificationVerificationTypeId', 'LEFT');
    $this->where('(a.IsDeleted IS NULL OR a.IsDeleted=0)');

    if ($externalID != null || $uid != null) {
      if ($verificationType == null) {
        $this->where(array('a.ExternalId' => $externalID));
      } else {
        if ($externalID != null) {
          if ($transactionId != null) {
            $this->where(
              array(
                "a.ExternalId" => $externalID,
                "a.OCR" => $ocr,
                'a.VerificationType' => $verificationType,
                'a.TransactionId' => $transactionId
              )
            );
          } else {
            $this->where(
              array(
                "a.ExternalId" => $externalID,
                "a.OCR" => $ocr,
                'a.VerificationType' => $verificationType,
              )
            );
          }
        } else {

          $this->where(
            array(
              "a.AppStoreUserId" => $uid,
              "a.ExternalId" => null,
              'a.VerificationType' => $verificationType,
              "a.OCR" => $ocr
            )
          );
        }
      }
    }

    $res = $this
      ->orderBy($sort, $sortorder)
      ->findAll($limit, $offset);
    //echo $this->db->getLastQuery();


    return $res;
  }

  function updateUpload($ExternalId = null, $uid = null)
  {
    if ($ExternalId && $uid) {

      $uploadID = $this
        ->where(
          array(
            "AppStoreUserId" => $uid,
            "ExternalId" => null,
          )
        )
        //->where('AppStoreUserId', $uid)
        ->set(['ExternalId' => $ExternalId])
        ->update();
    }
  }

  function updateUploadUid($newUid = null, $currentUid = null)
  {
    if ($newUid && $currentUid) {

      $uploadID = $this
        ->where('ExternalId', null, 'AppStoreUserId', $currentUid)
        ->set([
          'AppStoreUserId' => $newUid,
          'ExternalId' => $newUid
        ])
        ->update();
    }
  }


  public function deleteData(array $data)
  {
    $existing = $this->where('UploadedFileId', $data['UploadedFileId'])
      ->first();

    if ($existing != null) {
      //echo $this->db->getLastQuery();
      $upload_id = $this->update($data['UploadedFileId'], $data);
      //echo $this->db->getLastQuery();
      unset($data);
      $data['id'] = $upload_id;
      return  $data;
    } else {
      return null;
    }
  }
}
